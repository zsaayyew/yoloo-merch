<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * PRD-03 ORD — Keranjang guest berbasis token cookie (K-5).
 */
class Cart extends Model
{
    /** Masa berlaku keranjang (ORD-FR-003). */
    public const LIFETIME_DAYS = 7;

    /** Batas baris item per keranjang (ORD-FR-002, usulan PRD). */
    public const MAX_ITEMS = 20;

    protected $fillable = [
        'token',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
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
