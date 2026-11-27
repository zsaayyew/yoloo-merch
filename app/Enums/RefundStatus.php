<?php

namespace App\Enums;

/**
 * Status catatan refund (AI_CONTEXT bagian 5).
 * Hanya Selesai yang mengurangi Pembayaran Bersih (FIN-BR-005).
 * Ringkasan "Tidak Ada / Sebagian / Penuh" dihitung PaymentLedgerService, bukan enum ini.
 */
enum RefundStatus: string
{
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }
}
