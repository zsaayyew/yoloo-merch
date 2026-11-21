<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * PRD-03 ORD — Item keranjang, dibentuk dari ConfigurationPayload CAT.
 * Harga di sini bukan acuan final: CartService selalu menghitung ulang via PriceCalculator.
 */
class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_name_snapshot',
        'options_snapshot',
        'option_value_ids',
        'configuration_hash',
        'quantity',
        'price_model',
        'estimated_unit_price',
        'estimated_subtotal',
        'pricing_version',
    ];

    protected function casts(): array
    {
        return [
            'options_snapshot' => 'array',
            'option_value_ids' => 'array',
            'quantity' => 'integer',
            'estimated_unit_price' => 'integer',
            'estimated_subtotal' => 'integer',
            'pricing_version' => 'integer',
        ];
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(OrderAttachment::class);
    }

    /**
     * Hash konfigurasi untuk menggabungkan item identik.
     * Urutan option_value_ids tidak berpengaruh.
     */
    public static function makeConfigurationHash(int $productId, array $optionValueIds): string
    {
        $ids = array_map('intval', $optionValueIds);
        sort($ids);

        return hash('sha256', $productId.'|'.implode(',', $ids));
    }
}
