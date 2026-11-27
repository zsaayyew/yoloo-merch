<?php

declare(strict_types=1);

namespace App\Services\Sales;

use App\Enums\RequestStatus;
use App\Models\OrderRequest;
use App\Models\User;
use App\Services\Audit\AuditLogger;
use App\Services\Finance\FinanceRuleException;
use Illuminate\Support\Facades\DB;

/**
 * PRD-05 FIN | FIN-FR-003, FIN-FR-004 - Penawaran custom request.
 *
 * Mengisi nominal dan syarat penawaran. Perubahan status ke "Penawaran Dikirim" TIDAK ditulis di sini,
 * tetapi lewat StatusTransitionService (satu-satunya tempat yang boleh mengubah status request).
 */
class QuotationService
{
    public function __construct(
        private readonly StatusTransitionService $transitions,
        private readonly ?AuditLogger $auditLogger = null,
    ) {
    }

    /** Penawaran bisa dibuat saat Ditinjau Admin, dan direvisi saat Penawaran Dikirim. */
    public static function canQuote(OrderRequest $request): bool
    {
        return in_array($request->status, [RequestStatus::UnderReview, RequestStatus::QuotationSent], true);
    }

    /**
     * Simpan penawaran. Jika request masih "Ditinjau Admin", statusnya otomatis menjadi
     * "Penawaran Dikirim". Jika sudah "Penawaran Dikirim", hanya isi penawaran yang direvisi.
     *
     * @throws FinanceRuleException
     */
    public function save(OrderRequest $request, int $amount, ?string $terms, ?User $actor = null): OrderRequest
    {
        return DB::transaction(function () use ($request, $amount, $terms, $actor): OrderRequest {
            $locked = OrderRequest::query()->whereKey($request->getKey())->lockForUpdate()->firstOrFail();

            if (! self::canQuote($locked)) {
                throw new FinanceRuleException(
                    'Penawaran hanya bisa dibuat saat request berstatus Ditinjau Admin, atau direvisi saat Penawaran Dikirim.'
                );
            }

            if ($amount <= 0) {
                throw new FinanceRuleException('Nominal penawaran harus lebih dari nol.');
            }

            $oldAmount = $locked->quotation_amount;
            $alreadySent = $locked->status === RequestStatus::QuotationSent;

            $locked->update([
                'quotation_amount' => $amount,
                'quotation_terms' => filled($terms) ? trim((string) $terms) : null,
            ]);

            if ($alreadySent) {
                $this->audit()->log('request.quotation_revised', $locked, null, [
                    'quotation_amount' => ['old' => $oldAmount, 'new' => $amount],
                ]);

                return $locked->refresh();
            }

            return $this->transitions->changeRequestStatus($locked, RequestStatus::QuotationSent, $actor);
        });
    }

    private function audit(): AuditLogger
    {
        return $this->auditLogger ?? app(AuditLogger::class);
    }
}
