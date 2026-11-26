<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * PRD-05 FIN — Histori perubahan status pengerjaan order (FIN-FR-010, FIN-BR-007).
 * Append-only: tidak boleh diubah atau dihapus (FIN-BR-009). Tulis hanya lewat
 * App\Services\Sales\StatusTransitionService.
 */
class OrderStatusHistory extends Model
{
    /** Tabel hanya punya changed_at, tanpa created_at/updated_at. */
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'previous_status',
        'new_status',
        'changed_by_user_id',
        'changed_at',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'previous_status' => OrderStatus::class,
            'new_status' => OrderStatus::class,
            'changed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (): never {
            throw new LogicException('Histori status bersifat append-only dan tidak boleh diubah.');
        });

        static::deleting(function (): never {
            throw new LogicException('Histori status bersifat append-only dan tidak boleh dihapus.');
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
