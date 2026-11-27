<?php

namespace App\Models;

use App\Enums\PaymentRecordStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LogicException;

/**
 * PRD-05 FIN — Catatan pembayaran QRIS (FIN-FR-008, FIN-FR-009).
 * Tidak boleh dihapus (FIN-BR-009): salah catat diselesaikan dengan Void oleh Owner.
 * Ubah hanya lewat service Finance, bukan langsung dari controller.
 */
class Payment extends Model
{
    public const METHOD_QRIS = 'qris';

    protected $fillable = [
        'order_id',
        'payment_date',
        'amount',
        'method',
        'reference',
        'note',
        'status',
        'recorded_by_user_id',
        'void_reason',
        'voided_by_user_id',
        'voided_at',
        'submission_key',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount' => 'integer',
            'status' => PaymentRecordStatus::class,
            'voided_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (): never {
            throw new LogicException('Catatan pembayaran tidak boleh dihapus. Gunakan Void oleh Owner.');
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by_user_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function isValid(): bool
    {
        return $this->status === PaymentRecordStatus::Valid;
    }
}
