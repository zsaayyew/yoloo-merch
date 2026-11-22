<?php

namespace App\Services\Ordering;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SubmissionToken;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * PRD-03 ORD — Submit order direct purchase (ORD-FR-008, ORD-FR-013, ORD-FR-014).
 *
 * Dalam satu DB::transaction():
 *   1. kunci dan cek token sekali pakai (kiriman kedua -> order yang sama)
 *   2. hitung ulang semua item lewat CAT; jika ada yang tidak tersedia -> tolak
 *   3. bandingkan dengan harga yang dilihat pelanggan di ringkasan; jika beda -> tolak,
 *      pelanggan diminta menyetujui ulang
 *   4. cari/buat pelanggan, buat order (direct, website, awaiting_confirmation, unpaid),
 *      item snapshot, dan pindahkan lampiran desain per item
 *   5. tandai token terpakai, kosongkan keranjang
 * Setelah tersimpan, semua perubahan status dan harga final dilakukan FIN.
 * QRIS / instruksi pembayaran TIDAK pernah dibaca atau dibuat di sini.
 */
class OrderSubmissionService
{
    private const MAX_CODE_ATTEMPTS = 3;

    /** Kunci pengaturan usaha yang disepakati (AI_CONTEXT bagian 7 poin 10). */
    private const PICKUP_KEYS = ['pickup_location', 'pickup_hours', 'address', 'operating_hours'];

    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderCodeGenerator $codeGenerator,
        private readonly AttachmentService $attachmentService,
    ) {}

    public function issueToken(string $sessionId): SubmissionToken
    {
        return SubmissionToken::create([
            'token' => Str::random(64),
            'form_type' => SubmissionToken::TYPE_CHECKOUT,
            'session_id' => $sessionId,
            'expires_at' => now()->addHours(SubmissionToken::LIFETIME_HOURS),
        ]);
    }

    /**
     * Harga yang dilihat pelanggan di ringkasan, untuk dibandingkan saat submit.
     *
     * @return array{items: array<int, int>, total: int}
     */
    public function priceFingerprint(array $summary): array
    {
        return [
            'items' => $summary['rows']->mapWithKeys(fn (array $row) => [$row['item']->id => $row['subtotal']])->all(),
            'total' => $summary['estimated_total'],
        ];
    }

    /**
     * @param  array{name: string, whatsapp_number: string, email?: string|null, notes?: string|null}  $customerData
     * @param  array{items: array<int, int>, total: int}|null  $seenPrices
     *
     * @throws ValidationException token tidak valid, keranjang kosong/tidak tersedia, atau harga berubah
     */
    public function submit(?Cart $cart, array $customerData, string $token, string $sessionId, ?array $seenPrices): Order
    {
        return DB::transaction(function () use ($cart, $customerData, $token, $sessionId, $seenPrices) {
            $submission = SubmissionToken::query()
                ->where('token', $token)
                ->where('form_type', SubmissionToken::TYPE_CHECKOUT)
                ->lockForUpdate()
                ->first();

            if (! $submission || $submission->session_id !== $sessionId) {
                $this->fail('submission_token', 'Formulir sudah kedaluwarsa. Periksa kembali ringkasan, lalu kirim ulang.');
            }

            // Tombol ditekan dua kali / halaman dimuat ulang: kembalikan order yang sama.
            if ($submission->isUsed() && $submission->order_id) {
                return $submission->order;
            }

            if ($submission->isExpired()) {
                $this->fail('submission_token', 'Formulir sudah kedaluwarsa. Periksa kembali ringkasan, lalu kirim ulang.');
            }

            $summary = $this->cartService->summary($cart);

            if ($summary['rows']->isEmpty()) {
                $this->fail('cart', 'Keranjang kosong. Tambahkan produk terlebih dahulu.');
            }

            if ($summary['has_unavailable']) {
                $this->fail('cart', 'Ada produk yang tidak tersedia. Hapus atau ubah dulu di keranjang.');
            }

            if ($seenPrices === null || $this->priceFingerprint($summary) != $seenPrices) {
                $this->fail('price_changed', 'Estimasi harga berubah. Periksa ringkasan terbaru, lalu setujui dan kirim ulang.');
            }

            $customer = Customer::resolveFromContact($customerData);
            $order = $this->createOrder($customer, $summary['estimated_total'], $customerData['notes'] ?? null);

            foreach ($summary['rows'] as $row) {
                $item = $row['item'];

                $orderItem = $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name_snapshot' => $item->product_name_snapshot,
                    'options_snapshot' => $item->options_snapshot ?? [],
                    'quantity' => $item->quantity,
                    'price_model' => $item->price_model,
                    'unit_price' => (int) $row['unit_price'],
                    'subtotal' => (int) $row['subtotal'],
                    'price_status' => OrderItem::PRICE_ESTIMATE,
                ]);

                $this->attachmentService->attachToOrderItem($order, $orderItem, $item->id);
            }

            $submission->update(['used_at' => now(), 'order_id' => $order->id]);

            // Kosongkan keranjang (ORD-FR-008 langkah 6).
            $cart->items()->delete();
            $cart->delete();

            return $order;
        });
    }

    /**
     * Informasi pengambilan dari pengaturan usaha ADM (ORD-FR-006).
     * Mengembalikan null per kolom jika ADM belum mengisinya, supaya tampilan tidak mengarang alamat.
     *
     * @return array{location: string|null, hours: string|null}
     */
    public function pickupInfo(): array
    {
        $values = [];

        try {
            if (Schema::hasColumns('site_settings', ['key', 'value'])) {
                $values = DB::table('site_settings')->whereIn('key', self::PICKUP_KEYS)->pluck('value', 'key')->all();
            }
        } catch (Throwable) {
            $values = [];
        }

        return [
            'location' => filled($values['pickup_location'] ?? null) ? $values['pickup_location'] : ($values['address'] ?? null),
            'hours' => filled($values['pickup_hours'] ?? null) ? $values['pickup_hours'] : ($values['operating_hours'] ?? null),
        ];
    }

    private function createOrder(Customer $customer, int $estimatedTotal, ?string $notes): Order
    {
        $attributes = [
            'customer_id' => $customer->id,
            'type' => OrderType::Direct,
            'channel' => OrderChannel::Website,
            'status' => OrderStatus::AwaitingConfirmation,
            'payment_status' => PaymentStatus::Unpaid,
            // Nilai awal = estimasi; harga final ditetapkan FIN (TransactionCalculator).
            'subtotal' => $estimatedTotal,
            'discount' => 0,
            'additional_fee' => 0,
            'total' => $estimatedTotal,
            'estimated_total' => $estimatedTotal,
            'fulfillment' => 'pickup',
            'notes' => $notes,
            'ordered_at' => now(),
        ];

        for ($attempt = 1; ; $attempt++) {
            try {
                return Order::create($attributes + ['code' => $this->codeGenerator->forOrder()]);
            } catch (UniqueConstraintViolationException $e) {
                if ($attempt >= self::MAX_CODE_ATTEMPTS) {
                    throw $e;
                }
            }
        }
    }

    private function fail(string $key, string $message): never
    {
        throw ValidationException::withMessages([$key => $message]);
    }
}
