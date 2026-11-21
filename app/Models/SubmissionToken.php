<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PRD-03 ORD — Token sekali pakai untuk mencegah kiriman ganda (ORD-FR-013).
 * Bukan kredensial cek status pesanan.
 */
class SubmissionToken extends Model
{
    public const TYPE_CHECKOUT = 'checkout';

    public const TYPE_CUSTOM_ORDER = 'custom_order';

    public const LIFETIME_HOURS = 2;

    protected $fillable = [
        'token',
        'form_type',
        'session_id',
        'order_id',
        'order_request_id',
        'used_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderRequest(): BelongsTo
    {
        return $this->belongsTo(OrderRequest::class);
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<', now());
    }
}
