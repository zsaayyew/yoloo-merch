<?php

declare(strict_types=1);

namespace App\Services\Finance;

use DomainException;

/**
 * Dilempar saat sebuah aturan bisnis keuangan (PRD bagian 19, FIN-BR-*) dilanggar.
 * Pesannya berbahasa Indonesia dan aman ditampilkan ke pengguna.
 * Controller/Form Request nanti menangkapnya dan mengubahnya menjadi pesan validasi.
 */
class FinanceRuleException extends DomainException
{
    /** Format Rupiah sederhana untuk pesan error, contoh: Rp125.000 */
    public static function rupiah(int $amount): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.');
    }
}
