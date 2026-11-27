<?php

declare(strict_types=1);

namespace App\Services\Finance;

use App\Enums\OrderStatus;
use App\Enums\PaymentRecordStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\Audit\AuditLogger;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

/**
 * PRD-05 FIN | FIN-FR-008 (catat pembayaran), FIN-FR-009 (Void, Owner saja)
 * FIN-BR-005, 006, 008, 009, 016, 019 | FIN-AT-005, 006, 008
 *
 * Mencatat pembayaran QRIS yang sudah diverifikasi manual. Payment tidak pernah dihapus;
 * salah catat diselesaikan dengan Void oleh Owner dengan alasan wajib.
 * Semua perubahan dalam satu transaksi dengan baris order dikunci, dan status pembayaran
 * order dihitung ulang di transaksi yang sama (tidak ada perubahan parsial jika gagal).
 */
class PaymentService
{
    public function __construct(
        private readonly PaymentLedgerService $ledger,
        private readonly OrderLedgerService $orderLedger,
        private readonly ?AuditLogger $auditLogger = null,
    ) {
    }

    /**
     * @param  string       $paymentDate    Tanggal bayar, format Y-m-d.
     * @param  string|null  $submissionKey  Token unik per tampilan form; mencegah pengiriman ganda.
     *
     * @throws FinanceRuleException
     */
    public function record(
        Order $order,
        int $amount,
        string $paymentDate,
        ?string $reference = null,
        ?string $note = null,
        string $method = Payment::METHOD_QRIS,
        ?string $submissionKey = null,
        ?User $actor = null,
    ): Payment {
        if (strtolower(trim($method)) !== Payment::METHOD_QRIS) {
            throw new FinanceRuleException('Metode pembayaran versi pertama hanya QRIS.');
        }

        if (! Carbon::canBeCreatedFromFormat($paymentDate, 'Y-m-d')) {
            throw new FinanceRuleException('Tanggal bayar tidak valid. Gunakan format tahun-bulan-tanggal.');
        }

        $cleanReference = $this->clean($reference);
        if ($cleanReference !== null && mb_strlen($cleanReference) > 100) {
            throw new FinanceRuleException('Referensi transaksi QRIS maksimal 100 karakter.');
        }

        $key = $this->clean($submissionKey);
        if ($key !== null && mb_strlen($key) > 64) {
            throw new FinanceRuleException('Kunci pengiriman tidak valid.');
        }

        try {
            return DB::transaction(function () use ($order, $amount, $paymentDate, $cleanReference, $note, $key, $actor): Payment {
                $locked = Order::query()->whereKey($order->getKey())->lockForUpdate()->firstOrFail();

                if ($key !== null && Payment::query()->where('submission_key', $key)->exists()) {
                    throw $this->duplicateSubmission();
                }

                $summary = $this->orderLedger->summary($locked);

                $this->ledger->assertPaymentAllowed(
                    $amount,
                    (int) $locked->total,
                    $summary['net_payment'],
                    $locked->status === OrderStatus::Cancelled
                );

                $payment = Payment::create([
                    'order_id' => $locked->getKey(),
                    'payment_date' => $paymentDate,
                    'amount' => $amount,
                    'method' => Payment::METHOD_QRIS,
                    'reference' => $cleanReference,
                    'note' => $this->clean($note),
                    'status' => PaymentRecordStatus::Valid,
                    'recorded_by_user_id' => $actor?->getKey(),
                    'submission_key' => $key,
                ]);

                $this->orderLedger->syncPaymentStatus($locked);

                return $payment;
            });
        } catch (UniqueConstraintViolationException) {
            throw $this->duplicateSubmission();
        }
    }

    /**
     * Void catatan pembayaran yang salah. Owner saja; alasan wajib; bukan Refund.
     *
     * @throws FinanceRuleException
     */
    public function void(Payment $payment, string $reason, ?User $actor): Payment
    {
        if (! ($actor?->isOwner() ?? false)) {
            throw new FinanceRuleException('Void pembayaran hanya dapat dilakukan oleh Owner.');
        }

        $cleanReason = $this->clean($reason);
        if ($cleanReason === null) {
            throw new FinanceRuleException('Alasan Void wajib diisi.');
        }

        return DB::transaction(function () use ($payment, $cleanReason, $actor): Payment {
            $locked = Payment::query()->whereKey($payment->getKey())->lockForUpdate()->firstOrFail();
            $order = Order::query()->whereKey($locked->order_id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== PaymentRecordStatus::Valid) {
                throw new FinanceRuleException('Hanya pembayaran berstatus Valid yang dapat di-void.');
            }

            // Void tidak boleh membuat total refund melebihi pembayaran valid (data tidak konsisten).
            if ($this->orderLedger->completedRefundsForPayment($locked) > 0) {
                throw new FinanceRuleException(
                    'Pembayaran ini sudah memiliki Refund Selesai, sehingga tidak dapat di-void.'
                );
            }

            $locked->update([
                'status' => PaymentRecordStatus::Void,
                'void_reason' => $cleanReason,
                'voided_by_user_id' => $actor?->getKey(),
                'voided_at' => now(),
            ]);

            $this->orderLedger->syncPaymentStatus($order);

            $this->audit()->log('payment.void', $locked, $cleanReason, [
                'status' => [
                    'old' => PaymentRecordStatus::Valid->value,
                    'new' => PaymentRecordStatus::Void->value,
                ],
                'amount' => $locked->amount,
                'order_id' => $order->getKey(),
            ]);

            return $locked;
        });
    }

    private function duplicateSubmission(): FinanceRuleException
    {
        return new FinanceRuleException(
            'Pembayaran ini sudah tercatat. Pengiriman ganda diabaikan, periksa riwayat pembayaran.'
        );
    }

    private function clean(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        return trim($value);
    }

    private function audit(): AuditLogger
    {
        return $this->auditLogger ?? app(AuditLogger::class);
    }
}
