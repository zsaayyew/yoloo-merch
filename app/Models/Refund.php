<?php

namespace App\Models;

use App\Enums\RefundStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * PRD-05 FIN — Catatan refund manual (FIN-FR-019). Hanya Owner yang mencatat.
 * Hanya status Selesai yang mengurangi Pembayaran Bersih.
 * Tidak boleh dihapus (FIN-BR-009).
 */
class Refund extends Model
{
    protected $fillable = [
        'order_id',
        'payment_id',
        'refund_date',
        'amount',
        'method',
        'reference',
        'reason',
        'status',
        'recorded_by_user_id',
        'submission_key',
    ];

    protected function casts(): array
    {
        return [
            'refund_date' => 'date',
            'amount' => 'integer',
            'status' => RefundStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (): never {
            throw new LogicException('Catatan refund tidak boleh dihapus.');
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === RefundStatus::Completed;
    }
}
