<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Finance\VoidPaymentRequest;
use App\Models\Payment;
use App\Services\Finance\FinanceRuleException;
use App\Services\Finance\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/** PRD-05 FIN - Void pembayaran, Owner saja (FIN-FR-009). Bukan refund. */
class PaymentVoidController extends Controller
{
    public function store(VoidPaymentRequest $request, Payment $payment, PaymentService $payments): RedirectResponse
    {
        Gate::authorize('void', $payment);

        try {
            $payments->void($payment, (string) $request->validated('reason'), $request->user());
        } catch (FinanceRuleException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.orders.show', $payment->order_id)
            ->with('success', 'Pembayaran di-void. Datanya tetap tersimpan dengan status Void.');
    }
}
