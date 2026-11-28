<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sales\ChangeOrderStatusRequest;
use App\Models\Order;
use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\StatusTransitionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/** PRD-05 FIN - Ubah status pengerjaan order (FIN-FR-010). Aturan ada di StatusTransitionService. */
class OrderStatusController extends Controller
{
    public function update(
        ChangeOrderStatusRequest $request,
        Order $order,
        StatusTransitionService $transitions,
    ): RedirectResponse {
        Gate::authorize('update', $order);

        $target = OrderStatus::from($request->validated('status'));

        // Pembatalan punya tombol dan hak akses sendiri (Owner, alasan wajib).
        if ($target === OrderStatus::Cancelled) {
            return back()->with('error', 'Pembatalan transaksi memakai tombol Batalkan Transaksi dan hanya dapat dilakukan Owner.');
        }

        try {
            $transitions->changeOrderStatus(
                $order,
                $target,
                $request->user(),
                $request->validated('note'),
            );
        } catch (FinanceRuleException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status transaksi diubah menjadi "'.$target->label().'".');
    }
}
