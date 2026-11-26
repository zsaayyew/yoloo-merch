<?php

declare(strict_types=1);

namespace App\Services\Sales;

use App\Enums\OrderStatus;
use App\Enums\RequestStatus;
use App\Models\Order;
use App\Models\OrderRequest;
use App\Models\OrderStatusHistory;
use App\Models\User;
use App\Services\Audit\AuditLogger;
use App\Services\Finance\FinanceRuleException;
use Illuminate\Support\Facades\DB;

/**
 * PRD-05 FIN | FIN-FR-010, FIN-FR-003, FIN-FR-004 | FIN-BR-007, FIN-BR-009, FIN-BR-015
 *
 * Satu-satunya tempat yang boleh mengubah status order dan request.
 * Controller tidak boleh mengubah kolom status langsung.
 *
 * Dua bagian:
 *  1. ATURAN (method assert dan allowed): murni, tanpa database.
 *  2. PENERAPAN (method change): menerapkan aturan di dalam transaksi database,
 *     menulis histori (order_status_histories) atau audit log (request, keputusan K-7).
 *
 * Sumber aturan:
 *  - Order  : AI_CONTEXT bagian 5. Urut maju satu langkah:
 *             awaiting_confirmation -> awaiting_payment -> processing -> ready_for_pickup -> completed.
 *             cancelled dari status mana pun sebelum completed, Owner saja, alasan wajib.
 *  - Request: PRD FLOW-FIN-001. Alur: new -> under_review -> (awaiting_info) -> quotation_sent
 *             -> approved -> converted. Rejected/Expired memerlukan alasan.
 *             "converted" hanya lewat konversi (RequestConversionService), bukan ubah status manual.
 *
 * Status pembayaran TIDAK diatur di sini (dihitung PaymentLedgerService) dan tidak
 * memengaruhi status pengerjaan secara otomatis.
 */
class StatusTransitionService
{
    /**
     * @param  AuditLogger|null  $auditLogger  Opsional supaya aturan murni bisa dites tanpa container.
     */
    public function __construct(private readonly ?AuditLogger $auditLogger = null)
    {
    }

    // ------------------------------------------------------------------
    // PENERAPAN (database)
    // ------------------------------------------------------------------

    /**
     * Ubah status pengerjaan order: validasi transisi, simpan, tulis histori.
     * Semua dalam satu transaksi; jika ditolak, tidak ada yang berubah.
     *
     * @param  User|null    $actor  Pengguna yang mengubah; null berarti sistem.
     * @param  string|null  $note   Catatan; wajib (alasan) saat Dibatalkan.
     * @return Order  Order terbaru setelah diubah.
     *
     * @throws FinanceRuleException
     */
    public function changeOrderStatus(Order $order, OrderStatus $to, ?User $actor = null, ?string $note = null): Order
    {
        return DB::transaction(function () use ($order, $to, $actor, $note): Order {
            // Baca ulang dengan kunci baris supaya dua admin tidak menimpa satu sama lain.
            $locked = Order::query()->whereKey($order->getKey())->lockForUpdate()->firstOrFail();
            $from = $locked->status;

            $this->assertOrderTransition($from, $to, $actor?->isOwner() ?? false, $note);

            $cleanNote = $this->isBlank($note) ? null : trim((string) $note);

            $attributes = ['status' => $to];
            if ($to === OrderStatus::Cancelled) {
                $attributes['cancellation_reason'] = $cleanNote;
                $attributes['cancelled_at'] = now();
            }
            $locked->update($attributes);

            OrderStatusHistory::create([
                'order_id' => $locked->getKey(),
                'previous_status' => $from,
                'new_status' => $to,
                'changed_by_user_id' => $actor?->getKey(),
                'changed_at' => now(),
                'note' => $cleanNote,
            ]);

            if ($to === OrderStatus::Cancelled) {
                $this->audit()->log('order.cancel', $locked, $cleanNote, [
                    'status' => ['old' => $from->value, 'new' => $to->value],
                ]);
            }

            return $locked;
        });
    }

    /**
     * Ubah status request. Histori dicatat di audit_logs (keputusan K-7).
     * Status "Dikonversi Menjadi Order" tidak lewat sini, tetapi lewat konversi.
     *
     * @param  string|null  $reason  Alasan; wajib untuk Ditolak dan Kedaluwarsa.
     * @return OrderRequest  Request terbaru setelah diubah.
     *
     * @throws FinanceRuleException
     */
    public function changeRequestStatus(OrderRequest $request, RequestStatus $to, ?User $actor = null, ?string $reason = null): OrderRequest
    {
        return DB::transaction(function () use ($request, $to, $reason): OrderRequest {
            $locked = OrderRequest::query()->whereKey($request->getKey())->lockForUpdate()->firstOrFail();
            $from = $locked->status;

            $this->assertRequestTransition($from, $to, $reason, $locked->quotation_amount);

            $cleanReason = $this->isBlank($reason) ? null : trim((string) $reason);

            $locked->update(['status' => $to]);

            $this->audit()->log('request.status_changed', $locked, $cleanReason, [
                'status' => ['old' => $from->value, 'new' => $to->value],
            ]);

            return $locked;
        });
    }

    // ------------------------------------------------------------------
    // ATURAN: ORDER
    // ------------------------------------------------------------------

    /**
     * Status order berikutnya yang boleh dipilih.
     *
     * @return array<int, OrderStatus>
     */
    public function allowedOrderTransitions(OrderStatus $from, bool $actorIsOwner): array
    {
        $allowed = [];

        $next = $this->nextOrderStatus($from);
        if ($next !== null) {
            $allowed[] = $next;
        }

        if ($actorIsOwner && $this->isOrderOpen($from)) {
            $allowed[] = OrderStatus::Cancelled;
        }

        return $allowed;
    }

    public function canChangeOrderStatus(OrderStatus $from, OrderStatus $to, bool $actorIsOwner): bool
    {
        return in_array($to, $this->allowedOrderTransitions($from, $actorIsOwner), true);
    }

    /** Alasan wajib hanya untuk pembatalan (FIN-BR-009). */
    public function orderReasonRequired(OrderStatus $to): bool
    {
        return $to === OrderStatus::Cancelled;
    }

    /**
     * @param  string|null  $note  Catatan perubahan; wajib (alasan) jika membatalkan.
     *
     * @throws FinanceRuleException jika transisi tidak valid; status tidak boleh diubah.
     */
    public function assertOrderTransition(
        OrderStatus $from,
        OrderStatus $to,
        bool $actorIsOwner,
        ?string $note = null
    ): void {
        if ($from === $to) {
            throw new FinanceRuleException("Status transaksi sudah {$to->label()}.");
        }

        if (! $this->isOrderOpen($from)) {
            throw new FinanceRuleException(
                "Status {$from->label()} adalah status akhir dan tidak dapat diubah."
            );
        }

        $existsForOwner = $this->canChangeOrderStatus($from, $to, true);

        if (! $existsForOwner) {
            throw new FinanceRuleException(
                "Status tidak dapat diubah dari {$from->label()} menjadi {$to->label()}."
            );
        }

        if ($to === OrderStatus::Cancelled && ! $actorIsOwner) {
            throw new FinanceRuleException('Pembatalan transaksi hanya dapat dilakukan oleh Owner.');
        }

        if ($this->orderReasonRequired($to) && $this->isBlank($note)) {
            throw new FinanceRuleException('Alasan pembatalan wajib diisi.');
        }
    }

    // ------------------------------------------------------------------
    // ATURAN: REQUEST
    // ------------------------------------------------------------------

    /**
     * Status request berikutnya yang boleh dipilih lewat ubah status manual.
     * "Dikonversi Menjadi Order" sengaja tidak ada di sini: hanya lewat konversi.
     *
     * @return array<int, RequestStatus>
     */
    public function allowedRequestTransitions(RequestStatus $from): array
    {
        return match ($from) {
            RequestStatus::New => [
                RequestStatus::UnderReview,
                RequestStatus::Rejected,
                RequestStatus::Expired,
            ],
            RequestStatus::UnderReview => [
                RequestStatus::AwaitingInfo,
                RequestStatus::QuotationSent,
                RequestStatus::Rejected,
                RequestStatus::Expired,
            ],
            RequestStatus::AwaitingInfo => [
                RequestStatus::UnderReview,
                RequestStatus::Rejected,
                RequestStatus::Expired,
            ],
            RequestStatus::QuotationSent => [
                RequestStatus::Approved,
                RequestStatus::Rejected,
                RequestStatus::Expired,
            ],
            // Approved hanya bisa dikonversi menjadi order; Rejected/Expired/Converted adalah status akhir.
            RequestStatus::Approved,
            RequestStatus::Rejected,
            RequestStatus::Expired,
            RequestStatus::Converted => [],
        };
    }

    /** Alasan wajib untuk Ditolak dan Kedaluwarsa (FLOW-FIN-001). */
    public function requestReasonRequired(RequestStatus $to): bool
    {
        return in_array($to, [RequestStatus::Rejected, RequestStatus::Expired], true);
    }

    /**
     * @param  string|null  $reason           Alasan; wajib untuk Ditolak dan Kedaluwarsa.
     * @param  int|null     $quotationAmount  Nominal penawaran pada request; wajib > 0 untuk Penawaran Dikirim.
     *
     * @throws FinanceRuleException jika transisi tidak valid; status tidak boleh diubah.
     */
    public function assertRequestTransition(
        RequestStatus $from,
        RequestStatus $to,
        ?string $reason = null,
        ?int $quotationAmount = null
    ): void {
        if ($from === $to) {
            throw new FinanceRuleException("Status request sudah {$to->label()}.");
        }

        if ($to === RequestStatus::Converted) {
            throw new FinanceRuleException(
                'Status "Dikonversi Menjadi Order" hanya berubah lewat tombol Konversi ke Order.'
            );
        }

        if ($this->isRequestFinal($from)) {
            throw new FinanceRuleException(
                "Status {$from->label()} adalah status akhir dan tidak dapat diubah."
            );
        }

        if (! in_array($to, $this->allowedRequestTransitions($from), true)) {
            throw new FinanceRuleException(
                "Status tidak dapat diubah dari {$from->label()} menjadi {$to->label()}."
            );
        }

        if ($this->requestReasonRequired($to) && $this->isBlank($reason)) {
            throw new FinanceRuleException("Alasan wajib diisi untuk status {$to->label()}.");
        }

        if ($to === RequestStatus::QuotationSent && ($quotationAmount === null || $quotationAmount <= 0)) {
            throw new FinanceRuleException(
                'Isi nominal penawaran lebih dari nol sebelum mengubah status menjadi Penawaran Dikirim.'
            );
        }
    }

    /**
     * FIN-FR-004: request hanya boleh dikonversi jika Disetujui dan belum pernah dikonversi.
     * Pengecekan ulang di dalam transaksi database (dengan kunci baris) tetap wajib
     * di RequestConversionService untuk mencegah konversi ganda saat dua admin menekan bersamaan.
     *
     * @throws FinanceRuleException
     */
    public function assertRequestConvertible(RequestStatus $status, bool $alreadyConverted): void
    {
        if ($alreadyConverted || $status === RequestStatus::Converted) {
            throw new FinanceRuleException('Request ini sudah dikonversi menjadi order.');
        }

        if ($status !== RequestStatus::Approved) {
            throw new FinanceRuleException(
                'Hanya request berstatus Disetujui Pelanggan yang dapat dikonversi menjadi order.'
            );
        }
    }

    // ------------------------------------------------------------------
    // Internal
    // ------------------------------------------------------------------

    private function audit(): AuditLogger
    {
        return $this->auditLogger ?? app(AuditLogger::class);
    }

    private function nextOrderStatus(OrderStatus $from): ?OrderStatus
    {
        return match ($from) {
            OrderStatus::AwaitingConfirmation => OrderStatus::AwaitingPayment,
            OrderStatus::AwaitingPayment => OrderStatus::Processing,
            OrderStatus::Processing => OrderStatus::ReadyForPickup,
            OrderStatus::ReadyForPickup => OrderStatus::Completed,
            OrderStatus::Completed, OrderStatus::Cancelled => null,
        };
    }

    /** Order masih berjalan (belum Selesai dan belum Dibatalkan). */
    private function isOrderOpen(OrderStatus $status): bool
    {
        return $status !== OrderStatus::Completed && $status !== OrderStatus::Cancelled;
    }

    private function isRequestFinal(RequestStatus $status): bool
    {
        return in_array($status, [
            RequestStatus::Rejected,
            RequestStatus::Expired,
            RequestStatus::Converted,
        ], true);
    }

    private function isBlank(?string $value): bool
    {
        return $value === null || trim($value) === '';
    }
}
