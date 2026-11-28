<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Finance\StoreRefundRequest;
use App\Models\Payment;
use App\Models\Refund;
use App\Services\Finance\FinanceRuleException;
use App\Services\Finance\RefundService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/** PRD-05 FIN - Catat refund manual, Owner saja (FIN-FR-019). Aturan ada di RefundService. */
class RefundController extends Controller
{
    public function store(StoreRefundRequest $request, Payment $payment, RefundService $refunds): RedirectResponse
    {
        Gate::authorize('create', Refund::class);

        $data = $request->validated();

        try {
            $refunds->record(
                $payment,
                (int) $data['amount'],
                $data['refund_date'],
                $data['method'],
                $data['reason'],
                $data['reference'] ?? null,
                $data['submission_key'] ?? null,
                $request->user(),
            );
        } catch (FinanceRuleException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.orders.show', $payment->order_id)
            ->with('success', 'Refund berhasil dicatat.');
    }
}
