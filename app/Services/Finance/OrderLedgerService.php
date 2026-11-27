<?php

declare(strict_types=1);

namespace App\Services\Finance;

use App\Enums\PaymentRecordStatus;
use App\Enums\RefundStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;

/**
 * PRD-05 FIN | FIN-FR-008, FIN-FR-009, FIN-FR-019 | FIN-BR-005, FIN-BR-006
 *
 * Jembatan antara database dan PaymentLedgerService (yang murni menghitung).
 * Menjumlahkan HANYA Payment Valid dan Refund Selesai (Void dan Refund Dibatalkan tidak dihitung),
 * lalu menyimpan status pembayaran hasil hitung ke orders.payment_status.
 * Status pembayaran tidak pernah diinput manual dan tidak mengubah status pengerjaan.
 */
class OrderLedgerService
{
    public function __construct(private readonly PaymentLedgerService $ledger)
    {
    }

    /**
     * @return array{
     *     total_bill: int,
     *     valid_payments_total: int,
     *     completed_refunds_total: int,
     *     net_payment: int,
     *     remaining: int,
     *     payment_status: \App\Enums\PaymentStatus,
     *     refund_status: string,
     *     refund_status_label: string
     * }
     */
    public function summary(Order $order): array
    {
        $validPayments = (int) Payment::query()
            ->where('order_id', $order->getKey())
            ->where('status', PaymentRecordStatus::Valid->value)
            ->sum('amount');

        $completedRefunds = (int) Refund::query()
            ->where('order_id', $order->getKey())
            ->where('status', RefundStatus::Completed->value)
            ->sum('amount');

        return $this->ledger->summarize((int) $order->total, $validPayments, $completedRefunds);
    }

    /**
     * Hitung ulang dan simpan status pembayaran order. Panggil di dalam transaksi
     * yang sama dengan perubahan Payment atau Refund.
     */
    public function syncPaymentStatus(Order $order): Order
    {
        $summary = $this->summary($order);

        if ($order->payment_status !== $summary['payment_status']) {
            $order->update(['payment_status' => $summary['payment_status']]);
        }

        return $order;
    }

    /** Jumlah Refund Selesai atas satu Payment sumber. */
    public function completedRefundsForPayment(Payment $payment): int
    {
        return (int) Refund::query()
            ->where('payment_id', $payment->getKey())
            ->where('status', RefundStatus::Completed->value)
            ->sum('amount');
    }
}
