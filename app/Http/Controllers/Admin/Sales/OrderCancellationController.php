<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sales\CancelOrderRequest;
use App\Models\Order;
use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\StatusTransitionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/**
 * PRD-05 FIN - Pembatalan transaksi (FIN-FR-016). Owner saja, alasan wajib.
 * Payment dan refund tetap tersimpan; pengembalian dana dicatat terpisah lewat refund.
 */
class OrderCancellationController extends Controller
{
    public function store(
        CancelOrderRequest $request,
        Order $order,
        StatusTransitionService $transitions,
    ): RedirectResponse {
        Gate::authorize('cancel', $order);

        try {
            $transitions->changeOrderStatus(
                $order,
                OrderStatus::Cancelled,
                $request->user(),
                $request->validated('reason'),
            );
        } catch (FinanceRuleException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Transaksi dibatalkan. Pembayaran dan refund yang sudah tercatat tetap tersimpan.');
    }
}
