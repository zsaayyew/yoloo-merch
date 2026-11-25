<?php

declare(strict_types=1);

namespace App\Services\Finance;

use App\Enums\PaymentStatus;

/**
 * PRD-05 FIN | FIN-FR-008, FIN-FR-019 | FIN-BR-005, 006, 008, 019
 *
 * Satu-satunya tempat yang boleh menghitung:
 *   Pembayaran Bersih = Σ Pembayaran Valid − Σ Refund Selesai
 *   Sisa Tagihan      = Total Tagihan − Pembayaran Bersih
 *   Status pembayaran (otomatis, tidak pernah diinput manual)
 *   Ringkasan status refund (Tidak Ada / Sebagian / Penuh)
 *
 * Service ini menerima TOTAL yang sudah dijumlahkan. Yang menjumlahkan wajib
 * hanya memasukkan Payment berstatus Valid dan Refund berstatus Selesai;
 * Payment Void dan Refund Dibatalkan tidak boleh ikut dihitung (FIN-BR-005).
 */
class PaymentLedgerService
{
    public const REFUND_NONE = 'none';

    public const REFUND_PARTIAL = 'partial';

    public const REFUND_FULL = 'full';

    /**
     * @param  int  $totalBill               Total tagihan transaksi (orders.total).
     * @param  int  $validPaymentsTotal      Σ nominal Payment berstatus Valid.
     * @param  int  $completedRefundsTotal   Σ nominal Refund berstatus Selesai.
     * @return array{
     *     total_bill: int,
     *     valid_payments_total: int,
     *     completed_refunds_total: int,
     *     net_payment: int,
     *     remaining: int,
     *     payment_status: PaymentStatus,
     *     refund_status: string,
     *     refund_status_label: string
     * }
     *
     * @throws FinanceRuleException jika data tidak konsisten (bukan input pengguna biasa).
     */
    public function summarize(int $totalBill, int $validPaymentsTotal, int $completedRefundsTotal): array
    {
        if ($totalBill < 0 || $validPaymentsTotal < 0 || $completedRefundsTotal < 0) {
            throw new FinanceRuleException('Total tagihan, pembayaran, dan refund tidak boleh negatif.');
        }

        if ($completedRefundsTotal > $validPaymentsTotal) {
            throw new FinanceRuleException(
                'Data tidak konsisten: total Refund Selesai melebihi total Pembayaran Valid.'
            );
        }

        $netPayment = $validPaymentsTotal - $completedRefundsTotal;

        if ($netPayment > $totalBill) {
            throw new FinanceRuleException(
                'Data tidak konsisten: Pembayaran Bersih melebihi total tagihan (lihat FIN-BR-008 dan FIN-BR-020).'
            );
        }

        $remaining = $totalBill - $netPayment;

        $refundStatus = $this->refundStatus($validPaymentsTotal, $completedRefundsTotal);

        return [
            'total_bill' => $totalBill,
            'valid_payments_total' => $validPaymentsTotal,
            'completed_refunds_total' => $completedRefundsTotal,
            'net_payment' => $netPayment,
            'remaining' => $remaining,
            'payment_status' => $this->paymentStatus($totalBill, $netPayment),
            'refund_status' => $refundStatus,
            'refund_status_label' => self::refundStatusLabel($refundStatus),
        ];
    }

    /**
     * FIN-BR-006: status pembayaran diturunkan dari Pembayaran Bersih.
     *   Belum Dibayar   : Pembayaran Bersih = 0
     *   Dibayar Sebagian: 0 < Pembayaran Bersih < Total Tagihan
     *   Lunas           : Pembayaran Bersih = Total Tagihan
     */
    public function paymentStatus(int $totalBill, int $netPayment): PaymentStatus
    {
        if ($netPayment === 0) {
            return PaymentStatus::Unpaid;
        }

        if ($netPayment < $totalBill) {
            return PaymentStatus::PartiallyPaid;
        }

        return PaymentStatus::Paid;
    }

    /**
     * Ringkasan refund per transaksi.
     * Penuh = seluruh Pembayaran Valid sudah dikembalikan (Refund Selesai = Pembayaran Valid dan > 0).
     * Catatan: PRD menyebut tiga nama status ini tetapi tidak merinci kondisinya;
     * kondisi di atas adalah turunan dari contoh FIN-AT-017/018.
     */
    public function refundStatus(int $validPaymentsTotal, int $completedRefundsTotal): string
    {
        if ($completedRefundsTotal === 0) {
            return self::REFUND_NONE;
        }

        if ($completedRefundsTotal === $validPaymentsTotal) {
            return self::REFUND_FULL;
        }

        return self::REFUND_PARTIAL;
    }

    public static function refundStatusLabel(string $refundStatus): string
    {
        return match ($refundStatus) {
            self::REFUND_NONE => 'Tidak Ada Refund',
            self::REFUND_PARTIAL => 'Refund Sebagian',
            self::REFUND_FULL => 'Refund Penuh',
            default => throw new FinanceRuleException("Status refund tidak dikenal: {$refundStatus}."),
        };
    }

    /**
     * FIN-FR-008 + FIN-BR-008 + FIN-EC-006: validasi pembayaran baru.
     * Panggil sebelum menyimpan Payment baru. Pembayaran tidak boleh melebihi Sisa Tagihan.
     *
     * @param  int   $amount          Nominal pembayaran baru.
     * @param  int   $totalBill       Total tagihan saat ini.
     * @param  int   $netPayment      Pembayaran Bersih saat ini (sebelum pembayaran baru).
     * @param  bool  $orderCancelled  True jika transaksi berstatus Dibatalkan.
     *
     * @throws FinanceRuleException
     */
    public function assertPaymentAllowed(int $amount, int $totalBill, int $netPayment, bool $orderCancelled = false): void
    {
        if ($orderCancelled) {
            throw new FinanceRuleException('Transaksi sudah dibatalkan, pembayaran baru ditolak.');
        }

        if ($amount <= 0) {
            throw new FinanceRuleException('Nominal pembayaran harus berupa angka Rupiah bulat lebih dari nol.');
        }

        if ($totalBill <= 0) {
            throw new FinanceRuleException('Pembayaran hanya dapat dicatat pada transaksi dengan total lebih dari nol.');
        }

        $remaining = $totalBill - $netPayment;

        if ($remaining <= 0) {
            throw new FinanceRuleException('Transaksi ini sudah lunas dan tidak memiliki sisa tagihan.');
        }

        if ($amount > $remaining) {
            throw new FinanceRuleException(sprintf(
                'Nominal pembayaran (%s) melebihi sisa tagihan (%s).',
                FinanceRuleException::rupiah($amount),
                FinanceRuleException::rupiah($remaining)
            ));
        }
    }

    /**
     * FIN-FR-019: saldo yang masih dapat di-refund dari satu Payment sumber.
     * Maksimum refund = nominal Payment Valid − Refund Selesai sebelumnya (atas Payment yang sama).
     */
    public function maxRefundable(int $paymentAmount, int $completedRefundsForPayment): int
    {
        return max(0, $paymentAmount - $completedRefundsForPayment);
    }

    /**
     * FIN-FR-019 + FIN-BR-019 + FIN-EC-013/014/015: validasi refund baru.
     * (Pembatasan hanya Owner dijaga di RefundPolicy, bukan di sini.)
     *
     * @param  int   $amount                       Nominal refund baru.
     * @param  bool  $paymentIsValid               False jika Payment sumber berstatus Void.
     * @param  int   $paymentAmount                Nominal Payment sumber.
     * @param  int   $completedRefundsForPayment   Σ Refund Selesai sebelumnya atas Payment sumber ini.
     *
     * @throws FinanceRuleException
     */
    public function assertRefundAllowed(
        int $amount,
        bool $paymentIsValid,
        int $paymentAmount,
        int $completedRefundsForPayment
    ): void {
        if (! $paymentIsValid) {
            throw new FinanceRuleException('Refund ditolak: Payment sumber berstatus Void.');
        }

        if ($amount <= 0) {
            throw new FinanceRuleException('Nominal refund harus berupa angka Rupiah bulat lebih dari nol.');
        }

        $max = $this->maxRefundable($paymentAmount, $completedRefundsForPayment);

        if ($amount > $max) {
            throw new FinanceRuleException(sprintf(
                'Nominal refund (%s) melebihi saldo yang dapat dikembalikan. Maksimum refund: %s.',
                FinanceRuleException::rupiah($amount),
                FinanceRuleException::rupiah($max)
            ));
        }
    }
}
