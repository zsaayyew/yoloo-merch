<?php

namespace App\Support;

/**
 * Format Rupiah tanpa desimal. Contoh: 125000 => "Rp125.000".
 */
class Money
{
    public static function format(int $amount): string
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}
