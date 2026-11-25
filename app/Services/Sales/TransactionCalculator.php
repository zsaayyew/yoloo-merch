<?php

declare(strict_types=1);

namespace App\Services\Sales;

use App\Services\Finance\FinanceRuleException;

/**
 * PRD-05 FIN | FIN-FR-007 | FIN-BR-003, FIN-BR-020
 *
 * Satu-satunya tempat yang boleh menghitung total transaksi.
 * Rumus: Total = Σ(kuantitas × harga satuan) − diskon + biaya tambahan.
 *
 * Semua angka adalah Rupiah bulat (integer). Angka dari browser tidak dipercaya:
 * hitung ulang di sini sebelum disimpan ke tabel orders / order_items.
 */
class TransactionCalculator
{
    /**
     * @param  array<int, array{quantity: int|string, unit_price: int|string}>  $items
     *         Kunci sama dengan kolom order_items (quantity, unit_price).
     * @param  int  $discount       Diskon dalam nominal Rupiah (>= 0).
     * @param  int  $additionalFee  Biaya tambahan dalam nominal Rupiah (>= 0).
     * @return array{items: array<int, int>, subtotal: int, discount: int, additional_fee: int, total: int}
     *         'items' berisi subtotal per item, urutannya sama dengan input.
     *
     * @throws FinanceRuleException
     */
    public function calculate(array $items, int $discount = 0, int $additionalFee = 0): array
    {
        if ($items === []) {
            throw new FinanceRuleException('Transaksi harus memiliki minimal satu item.');
        }

        $itemSubtotals = [];
        $subtotal = 0;

        foreach (array_values($items) as $index => $item) {
            $position = $index + 1;

            $quantity = $this->toInt($item['quantity'] ?? null);
            if ($quantity === null || $quantity < 1) {
                throw new FinanceRuleException("Kuantitas item ke-{$position} harus berupa bilangan bulat positif.");
            }

            $unitPrice = $this->toInt($item['unit_price'] ?? null);
            if ($unitPrice === null || $unitPrice < 0) {
                throw new FinanceRuleException("Harga satuan item ke-{$position} harus berupa angka Rupiah bulat dan tidak negatif.");
            }

            $lineSubtotal = $quantity * $unitPrice;
            $itemSubtotals[] = $lineSubtotal;
            $subtotal += $lineSubtotal;
        }

        if ($discount < 0) {
            throw new FinanceRuleException('Diskon tidak boleh negatif.');
        }

        if ($additionalFee < 0) {
            throw new FinanceRuleException('Biaya tambahan tidak boleh negatif.');
        }

        if ($discount > $subtotal) {
            throw new FinanceRuleException(sprintf(
                'Diskon (%s) tidak boleh melebihi subtotal (%s).',
                FinanceRuleException::rupiah($discount),
                FinanceRuleException::rupiah($subtotal)
            ));
        }

        // Karena diskon <= subtotal dan biaya tambahan >= 0, total tidak mungkin negatif.
        $total = $subtotal - $discount + $additionalFee;

        return [
            'items' => $itemSubtotals,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'additional_fee' => $additionalFee,
            'total' => $total,
        ];
    }

    /**
     * FIN-BR-020 / FIN-EC-007: total baru tidak boleh lebih kecil daripada Pembayaran Bersih.
     * Panggil sebelum menyimpan perubahan total pada transaksi yang sudah punya pembayaran.
     * Kalau melempar exception, total lama harus dipertahankan (jangan simpan apa pun).
     *
     * @throws FinanceRuleException
     */
    public function assertTotalNotBelowNetPayment(int $newTotal, int $netPayment): void
    {
        if ($newTotal < $netPayment) {
            throw new FinanceRuleException(sprintf(
                'Total baru (%s) tidak boleh lebih kecil dari Pembayaran Bersih (%s). '
                .'Koreksi pembayaran atau catat Refund Selesai terlebih dahulu.',
                FinanceRuleException::rupiah($newTotal),
                FinanceRuleException::rupiah($netPayment)
            ));
        }
    }

    /**
     * Terima integer atau string angka bulat ("3", "25000"); selain itu dianggap tidak valid.
     * Desimal ("2.5"), teks ("abc"), dan null ditolak.
     */
    private function toInt(mixed $value): ?int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && preg_match('/^-?\d+$/', $value) === 1) {
            return (int) $value;
        }

        return null;
    }
}
