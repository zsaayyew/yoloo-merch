<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Bersama ORD + FIN — Item order (snapshot, tidak diubah setelah dibuat).
 */
class OrderItem extends Model
{
    public const PRICE_ESTIMATE = 'estimate';
    public const PRICE_FINAL = 'final';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name_snapshot',
        'options_snapshot',
        'quantity',
        'price_model',
        'unit_price',
        'subtotal',
        'price_status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'options_snapshot' => 'array',
            'quantity' => 'integer',
            'unit_price' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(OrderAttachment::class);
    }
}
