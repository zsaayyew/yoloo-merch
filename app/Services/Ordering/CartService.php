<?php

namespace App\Services\Ordering;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\Catalog\ConfigurationPayloadBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * PRD-03 ORD — Keranjang guest (ORD-FR-001 s.d. 004, keputusan K-5).
 *
 * - Keranjang diidentifikasi token acak di cookie, berlaku 7 hari.
 * - Data item dibentuk oleh ConfigurationPayloadBuilder milik CAT, yang sekaligus
 *   memvalidasi opsi, minimum order, dan menolak produk quotation / non-direct.
 * - Harga TIDAK pernah diambil dari browser; selalu dihitung ulang lewat CAT.
 * - Item dengan konfigurasi identik digabung jumlahnya.
 * - "Ubah opsi" (alur alternatif ORD-FR-002): item lama diganti dengan konfigurasi baru.
 */
class CartService
{
    public const COOKIE_NAME = 'yoloo_cart';

    public function __construct(
        private readonly ConfigurationPayloadBuilder $payloadBuilder,
    ) {}

    /** Keranjang aktif milik pengunjung ini, atau null jika belum ada / kedaluwarsa. */
    public function current(Request $request): ?Cart
    {
        $token = $request->cookie(self::COOKIE_NAME);

        if (! is_string($token) || strlen($token) !== 64) {
            return null;
        }

        return Cart::query()
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();
    }

    /** Jumlah baris item (untuk badge navbar). */
    public function count(Request $request): int
    {
        return $this->current($request)?->items()->count() ?? 0;
    }

    /**
     * Tambah produk ke keranjang, atau ganti item lama jika $replace diisi ("Ubah opsi").
     *
     * @throws ValidationException jika konfigurasi tidak valid, produk tidak bisa dibeli langsung,
     *                             atau keranjang penuh
     */
    public function add(Request $request, Product $product, array $optionValueIds, int $quantity, ?CartItem $replace = null): CartItem
    {
        // Validasi + hitung harga oleh CAT sebelum menyentuh database.
        $payload = $this->payloadBuilder->build($product, $optionValueIds, $quantity);
        $hash = CartItem::makeConfigurationHash($payload['product_id'], array_column($payload['options'], 'option_value_id'));

        return DB::transaction(function () use ($request, $payload, $hash, $replace) {
            $cart = $this->currentOrCreate($request);

            if ($replace && $replace->cart_id !== $cart->id) {
                $replace = null; // bukan milik keranjang ini: abaikan, perlakukan sebagai tambah biasa
            }

            // Ubah opsi tanpa mengganti konfigurasi: cukup set jumlah baru.
            if ($replace && $replace->configuration_hash === $hash) {
                $replace->update($this->attributesFromPayload($payload, $hash));

                return $replace;
            }

            $existing = $cart->items()->where('configuration_hash', $hash)->lockForUpdate()->first();

            if ($existing) {
                // Gabungkan jumlah, lalu hitung ulang harga untuk jumlah baru.
                $merged = $this->payloadBuilder->build(
                    $existing->product,
                    $existing->option_value_ids,
                    $existing->quantity + $payload['quantity'],
                );
                $existing->update($this->attributesFromPayload($merged, $hash));
                $replace?->delete();

                return $existing;
            }

            $otherItems = $cart->items()->when($replace, fn ($q) => $q->whereKeyNot($replace->id))->count();
            if ($otherItems >= Cart::MAX_ITEMS) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang maksimal berisi ' . Cart::MAX_ITEMS . ' produk. Selesaikan atau hapus sebagian dulu.',
                ]);
            }

            $item = $cart->items()->create($this->attributesFromPayload($payload, $hash));
            $replace?->delete();

            return $item;
        });
    }

    /** Item milik keranjang pengunjung ini berdasarkan ID, atau null. */
    public function findOwnedItem(Request $request, ?int $itemId): ?CartItem
    {
        $cart = $this->current($request);

        if (! $cart || ! $itemId) {
            return null;
        }

        return $cart->items()->whereKey($itemId)->first();
    }

    /** @throws ValidationException jika jumlah baru tidak valid (misalnya di bawah minimum order) */
    public function updateQuantity(CartItem $item, int $quantity): CartItem
    {
        $payload = $this->payloadBuilder->build($item->product, $item->option_value_ids, $quantity);
        $item->update($this->attributesFromPayload($payload, $item->configuration_hash));

        return $item;
    }

    public function remove(CartItem $item): void
    {
        $item->attachments()->each(fn ($attachment) => $attachment->update(['cart_item_id' => null]));
        $item->delete();
    }

    /** Pastikan item memang milik keranjang pengunjung ini. */
    public function owns(Request $request, CartItem $item): bool
    {
        $cart = $this->current($request);

        return $cart !== null && $item->cart_id === $cart->id;
    }

    /**
     * Ringkasan keranjang dengan harga yang dihitung ulang saat ini (ORD-FR-004).
     *
     * @return array{rows: Collection, estimated_total: int, has_unavailable: bool, has_price_changes: bool}
     */
    public function summary(?Cart $cart): array
    {
        $rows = collect();

        if ($cart) {
            foreach ($cart->items()->with('product')->orderBy('id')->get() as $item) {
                $rows->push($this->refreshRow($item));
            }
        }

        return [
            'rows' => $rows,
            'estimated_total' => (int) $rows->where('available', true)->sum('subtotal'),
            'has_unavailable' => $rows->contains('available', false),
            'has_price_changes' => $rows->contains('price_changed', true),
        ];
    }

    /** Hitung ulang satu item. Jika produk/tarif tidak tersedia lagi, item ditandai tidak tersedia. */
    private function refreshRow(CartItem $item): array
    {
        $previousSubtotal = $item->estimated_subtotal;

        try {
            if (! $item->product) {
                throw ValidationException::withMessages(['product' => 'Produk ini sudah tidak tersedia.']);
            }

            $payload = $this->payloadBuilder->build($item->product, $item->option_value_ids, $item->quantity);
            $item->update($this->attributesFromPayload($payload, $item->configuration_hash));

            return [
                'item' => $item,
                'available' => true,
                'message' => null,
                'unit_price' => $item->estimated_unit_price,
                'subtotal' => (int) $item->estimated_subtotal,
                'price_changed' => $previousSubtotal !== null && $previousSubtotal !== $item->estimated_subtotal,
                'previous_subtotal' => $previousSubtotal,
            ];
        } catch (ValidationException $e) {
            return [
                'item' => $item,
                'available' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Produk ini tidak tersedia.',
                'unit_price' => null,
                'subtotal' => 0,
                'price_changed' => false,
                'previous_subtotal' => null,
            ];
        }
    }

    private function currentOrCreate(Request $request): Cart
    {
        $cart = $this->current($request) ?? Cart::create([
            'token' => Str::random(64),
            'expires_at' => now()->addDays(Cart::LIFETIME_DAYS),
        ]);

        // Perpanjang masa berlaku setiap ada aktivitas tambah produk.
        $cart->update(['expires_at' => now()->addDays(Cart::LIFETIME_DAYS)]);
        Cookie::queue(self::COOKIE_NAME, $cart->token, Cart::LIFETIME_DAYS * 24 * 60);

        return $cart;
    }

    private function attributesFromPayload(array $payload, string $hash): array
    {
        return [
            'product_id' => $payload['product_id'],
            'product_name_snapshot' => $payload['product_name_snapshot'],
            'options_snapshot' => $payload['options'],
            'option_value_ids' => array_column($payload['options'], 'option_value_id'),
            'configuration_hash' => $hash,
            'quantity' => $payload['quantity'],
            'price_model' => $payload['price_model'],
            'estimated_unit_price' => $payload['estimated_unit_price'],
            'estimated_subtotal' => $payload['estimated_total'],
            'pricing_version' => $payload['pricing_version'],
        ];
    }
}
