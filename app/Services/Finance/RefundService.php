<?php

declare(strict_types=1);

namespace App\Services\Finance;

use App\Enums\RefundStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\User;
use App\Services\Audit\AuditLogger;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

/**
 * PRD-05 FIN | FIN-FR-019 | FIN-BR-005, 009, 013, 019 | FIN-AT-017, 018, 019
 *
 * Mencatat refund manual yang SUDAH benar-benar dilakukan di luar sistem. Owner saja.
 * Refund disimpan sebagai entitas terpisah, terhubung ke Payment sumber, dan hanya status
 * Selesai yang mengurangi Pembayaran Bersih. Sistem tidak mengirim uang.
 * Refund tetap boleh dicatat pada transaksi yang sudah dibatalkan (FIN-BR-013).
 */
class RefundService
{
    public function __construct(
        private readonly PaymentLedgerService $ledger,
        private readonly OrderLedgerService $orderLedger,
        private readonly ?AuditLogger $auditLogger = null,
    ) {
    }

    /**
     * @param  string       $refundDate     Tanggal refund, format Y-m-d.
     * @param  string       $method         Metode pengembalian (teks bebas, maksimal 50 karakter).
     * @param  string|null  $submissionKey  Token unik per tampilan form; mencegah pengiriman ganda.
     *
     * @throws FinanceRuleException
     */
    public function record(
        Payment $payment,
        int $amount,
        string $refundDate,
        string $method,
        string $reason,
        ?string $reference = null,
        ?string $submissionKey = null,
        ?User $actor = null,
    ): Refund {
        if (! ($actor?->isOwner() ?? false)) {
            throw new FinanceRuleException('Refund hanya dapat dicatat oleh Owner.');
        }

        if (! Carbon::canBeCreatedFromFormat($refundDate, 'Y-m-d')) {
            throw new FinanceRuleException('Tanggal refund tidak valid. Gunakan format tahun-bulan-tanggal.');
        }

        $cleanMethod = $this->clean($method);
        if ($cleanMethod === null) {
            throw new FinanceRuleException('Metode pengembalian wajib diisi.');
        }
        if (mb_strlen($cleanMethod) > 50) {
            throw new FinanceRuleException('Metode pengembalian maksimal 50 karakter.');
        }

        $cleanReason = $this->clean($reason);
        if ($cleanReason === null) {
            throw new FinanceRuleException('Alasan refund wajib diisi.');
        }

        $cleanReference = $this->clean($reference);
        if ($cleanReference !== null && mb_strlen($cleanReference) > 100) {
            throw new FinanceRuleException('Referensi refund maksimal 100 karakter.');
        }

        $key = $this->clean($submissionKey);
        if ($key !== null && mb_strlen($key) > 64) {
            throw new FinanceRuleException('Kunci pengiriman tidak valid.');
        }

        try {
            return DB::transaction(function () use ($payment, $amount, $refundDate, $cleanMethod, $cleanReason, $cleanReference, $key, $actor): Refund {
                $order = Order::query()->whereKey($payment->order_id)->lockForUpdate()->firstOrFail();
                $lockedPayment = Payment::query()->whereKey($payment->getKey())->lockForUpdate()->firstOrFail();

                if ($key !== null && Refund::query()->where('submission_key', $key)->exists()) {
                    throw $this->duplicateSubmission();
                }

                $this->ledger->assertRefundAllowed(
                    $amount,
                    $lockedPayment->isValid(),
                    (int) $lockedPayment->amount,
                    $this->orderLedger->completedRefundsForPayment($lockedPayment)
                );

                $refund = Refund::create([
                    'order_id' => $order->getKey(),
                    'payment_id' => $lockedPayment->getKey(),
                    'refund_date' => $refundDate,
                    'amount' => $amount,
                    'method' => $cleanMethod,
                    'reference' => $cleanReference,
                    'reason' => $cleanReason,
                    'status' => RefundStatus::Completed,
                    'recorded_by_user_id' => $actor?->getKey(),
                    'submission_key' => $key,
                ]);

                $this->orderLedger->syncPaymentStatus($order);

                $this->audit()->log('refund.recorded', $refund, $cleanReason, [
                    'amount' => $amount,
                    'payment_id' => $lockedPayment->getKey(),
                    'order_id' => $order->getKey(),
                ]);

                return $refund;
            });
        } catch (UniqueConstraintViolationException) {
            throw $this->duplicateSubmission();
        }
    }

    private function duplicateSubmission(): FinanceRuleException
    {
        return new FinanceRuleException(
            'Refund ini sudah tercatat. Pengiriman ganda diabaikan, periksa riwayat refund.'
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
