<?php

namespace App\Support;

/**
 * Normalisasi nomor WhatsApp ke format 62xxxxxxxxxx.
 * Dipakai ORD (checkout, custom request) dan FIN (pelanggan manual).
 * Aturan: ORD-BR-009, FIN-BR-012.
 */
class PhoneNumber
{
    /**
     * Ubah 08xx / +628xx / 628xx / "0812-3456 7890" menjadi 628xx.
     * Contoh: "0812-3456-7890" => "6281234567890".
     */
    public static function normalize(string $number): string
    {
        $digits = preg_replace('/\D+/', '', $number) ?? '';

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        return $digits;
    }

    /** Valid jika hasil normalisasi diawali 62 dan panjangnya 10–15 digit. */
    public static function isValid(string $number): bool
    {
        $normalized = self::normalize($number);

        return str_starts_with($normalized, '62')
            && strlen($normalized) >= 10
            && strlen($normalized) <= 15;
    }
}
