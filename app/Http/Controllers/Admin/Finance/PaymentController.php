<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Finance\StorePaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Finance\FinanceRuleException;
use App\Services\Finance\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/** PRD-05 FIN - Catat pembayaran QRIS (FIN-FR-008). Aturan ada di PaymentService. */
class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, Order $order, PaymentService $payments): RedirectResponse
    {
        Gate::authorize('create', Payment::class);

        $data = $request->validated();

        try {
            $payments->record(
                $order,
                (int) $data['amount'],
                $data['payment_date'],
                $data['reference'] ?? null,
                $data['note'] ?? null,
                Payment::METHOD_QRIS,
                $data['submission_key'] ?? null,
                $request->user(),
            );
        } catch (FinanceRuleException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }
}
