<?php

namespace App\Enums;

enum OrderType: string
{
    case Direct = 'direct';
    case Custom = 'custom';
    case Manual = 'manual';

    public function label(): string
    {
        return match ($this) {
            self::Direct => 'Direct purchase website',
            self::Custom => 'Hasil konversi custom request',
            self::Manual => 'Transaksi manual',
        };
    }
}
