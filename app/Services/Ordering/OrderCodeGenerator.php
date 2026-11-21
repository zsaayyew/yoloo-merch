<?php

namespace App\Services\Ordering;

use App\Models\Order;
use App\Models\OrderRequest;
use DateTimeInterface;
use RuntimeException;

/**
 * PRD-03 ORD — Pembuat kode pesanan/request (ORD-BR-008).
 *
 * Format:
 *   Direct purchase : WEB-YYMMDD-XXXX
 *   Custom request  : REQ-YYMMDD-XXXX
 * XXXX = 4 karakter acak (huruf besar + angka), dibuat dengan random_int
 * sehingga tidak berurutan dan tidak mudah ditebak.
 *
 * Karakter yang mirip (0/O, 1/I/L) tidak dipakai supaya kode mudah dibaca
 * pelanggan saat pickup atau saat menyebutkannya lewat WhatsApp.
 *
 * Catatan: pengecekan exists() di sini mengurangi bentrok, tetapi penjaga
 * terakhir tetap index unique di kolom `code` (migration 19 dan 20).
 * OrderSubmissionService wajib menangani UniqueConstraintViolationException
 * dengan membuat kode baru lalu mencoba lagi.
 */
class OrderCodeGenerator
{
    public const PREFIX_ORDER = 'WEB';
    public const PREFIX_REQUEST = 'REQ';

    private const ALPHABET = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
    private const RANDOM_LENGTH = 4;
    private const MAX_ATTEMPTS = 10;

    /** Kode unik untuk order direct purchase (tabel orders). */
    public function forOrder(): string
    {
        return $this->generateUnique(
            self::PREFIX_ORDER,
            fn (string $code) => Order::where('code', $code)->exists()
        );
    }

    /** Kode unik untuk custom request (tabel order_requests). */
    public function forRequest(): string
    {
        return $this->generateUnique(
            self::PREFIX_REQUEST,
            fn (string $code) => OrderRequest::where('code', $code)->exists()
        );
    }

    /**
     * Buat satu kode tanpa cek database. Dipakai internal dan untuk tes.
     * Contoh: make('WEB') => "WEB-261120-K7Q4".
     */
    public function make(string $prefix, ?DateTimeInterface $date = null): string
    {
        $date ??= now();

        return sprintf('%s-%s-%s', $prefix, $date->format('ymd'), $this->randomPart());
    }

    /**
     * Ulangi pembuatan kode sampai tidak bentrok dengan data yang ada.
     *
     * @param  callable(string): bool  $exists
     */
    private function generateUnique(string $prefix, callable $exists): string
    {
        for ($attempt = 0; $attempt < self::MAX_ATTEMPTS; $attempt++) {
            $code = $this->make($prefix);

            if (! $exists($code)) {
                return $code;
            }
        }

        throw new RuntimeException("Gagal membuat kode {$prefix} unik setelah " . self::MAX_ATTEMPTS . ' percobaan.');
    }

    private function randomPart(): string
    {
        $maxIndex = strlen(self::ALPHABET) - 1;
        $result = '';

        for ($i = 0; $i < self::RANDOM_LENGTH; $i++) {
            $result .= self::ALPHABET[random_int(0, $maxIndex)];
        }

        return $result;
    }
}
