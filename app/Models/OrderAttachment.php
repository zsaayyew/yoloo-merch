<?php

namespace App\Models;

use App\Enums\AttachmentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PRD-03 ORD — Lampiran desain/referensi pelanggan (ORD-FR-011).
 * File berada di disk "local" (privat). Hanya admin login yang boleh mengunduh (FIN).
 */
class OrderAttachment extends Model
{
    /** Disk penyimpanan (tidak publik). */
    public const DISK = 'local';

    public const TEMP_DIR = 'tmp-uploads';

    public const FINAL_DIR = 'order-attachments';

    /** Batas upload (ORD-BR-010). */
    public const MAX_FILES = 5;

    public const MAX_SIZE_KB = 10240; // 10 MB

    public const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'application/pdf'];

    /** File sementara dihapus setelah 24 jam. */
    public const TEMP_LIFETIME_HOURS = 24;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'order_request_id',
        'cart_item_id',
        'original_name',
        'stored_path',
        'mime_type',
        'size_bytes',
        'caption',
        'status',
        'session_token',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => AttachmentStatus::class,
            'size_bytes' => 'integer',
            'submitted_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function orderRequest(): BelongsTo
    {
        return $this->belongsTo(OrderRequest::class);
    }

    public function cartItem(): BelongsTo
    {
        return $this->belongsTo(CartItem::class);
    }

    public function scopeTemporary(Builder $query): Builder
    {
        return $query->where('status', AttachmentStatus::Temporary->value);
    }

    /** File sementara yang sudah lewat 24 jam (untuk command pembersihan). */
    public function scopeExpiredTemporary(Builder $query): Builder
    {
        return $query->temporary()
            ->where('created_at', '<', now()->subHours(self::TEMP_LIFETIME_HOURS));
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /** Contoh: 532 KB, 2,4 MB. */
    public function sizeForHumans(): string
    {
        $kb = $this->size_bytes / 1024;

        if ($kb < 1024) {
            return number_format(max($kb, 1), 0, ',', '.') . ' KB';
        }

        return number_format($kb / 1024, 1, ',', '.') . ' MB';
    }
}
