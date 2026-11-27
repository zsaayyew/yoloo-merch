<?php

declare(strict_types=1);

namespace App\Services\Sales;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\User;
use App\Services\Finance\FinanceRuleException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

/**
 * PRD-05 FIN | FIN-FR-005, FIN-FR-006, FIN-FR-007 | FIN-BR-002, 003, 004, 017 | FIN-AT-003
 *
 * Membuat transaksi manual dari kanal luar website (WhatsApp, Instagram, langsung).
 * Item disalin sebagai snapshot (nama, deskripsi, kuantitas, harga) sehingga perubahan katalog
 * tidak mengubah transaksi (FIN-BR-004). Total dihitung TransactionCalculator, bukan dari input.
 *
 * Asumsi yang perlu dikonfirmasi tim (PRD tidak menetapkan):
 *  - Kode: MAN-YYMMDD-NNNN, nomor urut per hari.
 *  - Status awal: Menunggu Pembayaran (harga sudah disepakati saat transaksi dibuat).
 *  - Item katalog memakai price_model "fixed"; item custom memakai "quotation".
 *  - Pelanggan nonaktif tidak boleh dipakai untuk transaksi baru.
 *  - Total transaksi harus lebih dari nol.
 *  - Ubah item transaksi yang sudah ada belum dibuat di service ini.
 */
class ManualTransactionService
{
    /** Nilai PriceModel::Fixed dan PriceModel::Quotation. Enum PriceModel belum diisi. */
    public const PRICE_MODEL_FIXED = 'fixed';

    public const PRICE_MODEL_QUOTATION = 'quotation';

    private const MAX_CODE_ATTEMPTS = 3;

    public function __construct(private readonly TransactionCalculator $calculator)
    {
    }

    /**
     * @param  array<int, array{name: string, quantity: int|string, unit_price: int|string, description?: string|null, product_id?: int|null, options?: array<string, mixed>}>  $items
     *         product_id diisi untuk item katalog; kosongkan untuk item custom.
     *
     * @throws FinanceRuleException
     */
    public function create(
        Customer $customer,
        array $items,
        int $discount = 0,
        int $additionalFee = 0,
        OrderChannel $channel = OrderChannel::WalkIn,
        ?string $notes = null,
        ?int $agreedDpAmount = null,
        ?string $paymentTermsNote = null,
        ?User $actor = null,
    ): Order {
        if (! $customer->is_active) {
            throw new FinanceRuleException('Pelanggan nonaktif tidak dapat dipakai untuk transaksi baru.');
        }

        if ($channel === OrderChannel::Website) {
            throw new FinanceRuleException('Transaksi manual hanya untuk kanal di luar website.');
        }

        $prepared = $this->prepareItems($items);
        $calc = $this->calculator->calculate($items, $discount, $additionalFee);

        if ($calc['total'] <= 0) {
            throw new FinanceRuleException('Total transaksi harus lebih dari nol.');
        }

        if ($agreedDpAmount !== null) {
            if ($agreedDpAmount <= 0) {
                throw new FinanceRuleException('DP yang disepakati harus lebih dari nol.');
            }
            if ($agreedDpAmount > $calc['total']) {
                throw new FinanceRuleException(sprintf(
                    'DP yang disepakati (%s) tidak boleh melebihi total transaksi (%s).',
                    FinanceRuleException::rupiah($agreedDpAmount),
                    FinanceRuleException::rupiah($calc['total'])
                ));
            }
        }

        for ($attempt = 1; $attempt <= self::MAX_CODE_ATTEMPTS; $attempt++) {
            try {
                return DB::transaction(function () use ($customer, $prepared, $calc, $channel, $notes, $agreedDpAmount, $paymentTermsNote, $actor): Order {
                    $order = Order::create([
                        'code' => $this->nextCode(),
                        'customer_id' => $customer->getKey(),
                        'type' => OrderType::Manual,
                        'channel' => $channel,
                        'status' => OrderStatus::AwaitingPayment,
                        'payment_status' => PaymentStatus::Unpaid,
                        'subtotal' => $calc['subtotal'],
                        'discount' => $calc['discount'],
                        'additional_fee' => $calc['additional_fee'],
                        'total' => $calc['total'],
                        'agreed_dp_amount' => $agreedDpAmount,
                        'payment_terms_note' => $this->clean($paymentTermsNote),
                        'fulfillment' => 'pickup',
                        'notes' => $this->clean($notes),
                        'ordered_at' => now(),
                    ]);

                    foreach ($prepared as $index => $item) {
                        OrderItem::create([
                            'order_id' => $order->getKey(),
                            'product_id' => $item['product_id'],
                            'product_name_snapshot' => $item['name'],
                            'options_snapshot' => $item['options'],
                            'quantity' => $item['quantity'],
                            'price_model' => $item['product_id'] !== null
                                ? self::PRICE_MODEL_FIXED
                                : self::PRICE_MODEL_QUOTATION,
                            'unit_price' => $item['unit_price'],
                            'subtotal' => $calc['items'][$index],
                            'price_status' => OrderItem::PRICE_FINAL,
                            'description' => $item['description'],
                        ]);
                    }

                    OrderStatusHistory::create([
                        'order_id' => $order->getKey(),
                        'previous_status' => null,
                        'new_status' => OrderStatus::AwaitingPayment,
                        'changed_by_user_id' => $actor?->getKey(),
                        'changed_at' => now(),
                        'note' => 'Transaksi manual dibuat',
                    ]);

                    return $order;
                });
            } catch (UniqueConstraintViolationException $e) {
                // Dua admin mendapat nomor yang sama bersamaan: coba ulang dengan nomor berikutnya.
                if ($attempt === self::MAX_CODE_ATTEMPTS) {
                    throw $e;
                }
            }
        }

        throw new FinanceRuleException('Gagal membuat nomor transaksi. Silakan coba lagi.');
    }

    /**
     * Validasi dan rapikan item. Angka dan harga divalidasi TransactionCalculator.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array{name: string, quantity: int, unit_price: int, description: string|null, product_id: int|null, options: array<string, mixed>}>
     *
     * @throws FinanceRuleException
     */
    private function prepareItems(array $items): array
    {
        $prepared = [];

        foreach (array_values($items) as $index => $item) {
            $position = $index + 1;
            $name = trim((string) ($item['name'] ?? ''));

            if ($name === '') {
                throw new FinanceRuleException("Nama item ke-{$position} wajib diisi.");
            }
            if (mb_strlen($name) > 255) {
                throw new FinanceRuleException("Nama item ke-{$position} maksimal 255 karakter.");
            }

            $productId = $item['product_id'] ?? null;
            if ($productId !== null) {
                if (! is_numeric($productId) || ! Product::query()->whereKey((int) $productId)->exists()) {
                    throw new FinanceRuleException("Produk katalog pada item ke-{$position} tidak ditemukan.");
                }
                $productId = (int) $productId;
            }

            $options = $item['options'] ?? [];
            if (! is_array($options)) {
                throw new FinanceRuleException("Opsi item ke-{$position} tidak valid.");
            }

            $prepared[] = [
                'name' => $name,
                'quantity' => (int) ($item['quantity'] ?? 0),
                'unit_price' => (int) ($item['unit_price'] ?? 0),
                'description' => $this->clean($item['description'] ?? null),
                'product_id' => $productId,
                'options' => $options,
            ];
        }

        return $prepared;
    }

    /** Nomor urut per hari: MAN-YYMMDD-0001, MAN-YYMMDD-0002, dan seterusnya. */
    private function nextCode(): string
    {
        $prefix = 'MAN-'.now()->format('ymd').'-';

        $last = Order::query()
            ->where('code', 'like', $prefix.'%')
            ->orderByDesc('code')
            ->value('code');

        $next = $last === null ? 1 : ((int) substr((string) $last, -4)) + 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    private function clean(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        return trim($value);
    }
}
