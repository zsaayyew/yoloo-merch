<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangeRequestStatusRequest;
use App\Models\Order;
use App\Models\OrderRequest;
use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\StatusTransitionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/** PRD-05 FIN - Ubah status custom request (FIN-FR-003). Aturan ada di StatusTransitionService. */
class RequestStatusController extends Controller
{
    public function update(
        ChangeRequestStatusRequest $request,
        OrderRequest $orderRequest,
        StatusTransitionService $transitions,
    ): RedirectResponse {
        Gate::authorize('manageRequests', Order::class);

        $target = RequestStatus::from($request->validated('status'));

        try {
            $transitions->changeRequestStatus(
                $orderRequest,
                $target,
                $request->user(),
                $request->validated('reason'),
            );
        } catch (FinanceRuleException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.requests.show', $orderRequest)
            ->with('success', 'Status request diubah menjadi "'.$target->label().'".');
    }
}
