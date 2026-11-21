<?php

namespace App\Enums;

enum OrderStatus: string
{
    case AwaitingConfirmation = 'awaiting_confirmation';
    case AwaitingPayment = 'awaiting_payment';
    case Processing = 'processing';
    case ReadyForPickup = 'ready_for_pickup';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::AwaitingConfirmation => 'Menunggu Konfirmasi',
            self::AwaitingPayment => 'Menunggu Pembayaran',
            self::Processing => 'Diproses',
            self::ReadyForPickup => 'Siap Diambil',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }
}
