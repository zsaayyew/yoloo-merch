<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuotationRequest;
use App\Models\OrderRequest;
use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\QuotationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/** PRD-05 FIN - Isi / revisi penawaran custom request (FIN-FR-003). */
class QuotationController extends Controller
{
    public function update(
        QuotationRequest $request,
        OrderRequest $orderRequest,
        QuotationService $quotations,
    ): RedirectResponse {
        Gate::authorize('update', $orderRequest);

        try {
            $quotations->save(
                $orderRequest,
                (int) $request->validated('quotation_amount'),
                $request->validated('quotation_terms'),
                $request->user(),
            );
        } catch (FinanceRuleException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.requests.show', $orderRequest)
            ->with('success', 'Penawaran disimpan. Kirim penawarannya ke pelanggan lewat WhatsApp.');
    }
}
