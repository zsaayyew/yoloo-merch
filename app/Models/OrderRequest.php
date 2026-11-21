<?php

namespace App\Models;

use App\Enums\OrderChannel;
use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Bersama ORD + FIN — Custom order request.
 * Request bukan penjualan sampai dikonversi menjadi Order oleh FIN.
 */
class OrderRequest extends Model
{
    protected $fillable = [
        'code',
        'customer_id',
        'channel',
        'status',
        'product_id',
        'product_name_snapshot',
        'options_snapshot',
        'estimate_snapshot',
        'description',
        'quantity',
        'specification',
        'desired_deadline',
        'budget',
        'notes',
        'quotation_amount',
        'quotation_terms',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'channel' => OrderChannel::class,
            'status' => RequestStatus::class,
            'options_snapshot' => 'array',
            'estimate_snapshot' => 'array',
            'quantity' => 'integer',
            'desired_deadline' => 'date',
            'budget' => 'integer',
            'quotation_amount' => 'integer',
            'submitted_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** Order hasil konversi (maksimal satu). */
    public function convertedOrder(): HasOne
    {
        return $this->hasOne(Order::class, 'source_request_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(OrderAttachment::class);
    }
}
