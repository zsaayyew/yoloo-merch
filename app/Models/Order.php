<?php

namespace App\Models;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Bersama ORD + FIN — Order.
 * Total, status pembayaran, dan perubahan status dikelola service FIN
 * (TransactionCalculator, PaymentLedgerService, StatusTransitionService).
 */
class Order extends Model
{
    protected $fillable = [
        'code',
        'customer_id',
        'type',
        'channel',
        'source_request_id',
        'status',
        'payment_status',
        'subtotal',
        'discount',
        'additional_fee',
        'total',
        'estimated_total',
        'agreed_dp_amount',
        'payment_terms_note',
        'fulfillment',
        'notes',
        'cancellation_reason',
        'cancelled_at',
        'ordered_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => OrderType::class,
            'channel' => OrderChannel::class,
            'status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
            'subtotal' => 'integer',
            'discount' => 'integer',
            'additional_fee' => 'integer',
            'total' => 'integer',
            'estimated_total' => 'integer',
            'agreed_dp_amount' => 'integer',
            'cancelled_at' => 'datetime',
            'ordered_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sourceRequest(): BelongsTo
    {
        return $this->belongsTo(OrderRequest::class, 'source_request_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(OrderAttachment::class);
    }
}
