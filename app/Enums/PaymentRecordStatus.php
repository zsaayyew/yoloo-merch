<?php

namespace App\Enums;

/**
 * Status catatan pembayaran (AI_CONTEXT bagian 5).
 * Valid dihitung sebagai Pembayaran Valid; Void tidak dihitung dan tidak dihapus (FIN-BR-005).
 */
enum PaymentRecordStatus: string
{
    case Valid = 'valid';
    case Void = 'void';

    public function label(): string
    {
        return match ($this) {
            self::Valid => 'Valid',
            self::Void => 'Void',
        };
    }
}
