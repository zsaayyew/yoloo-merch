<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConvertRequestRequest;
use App\Models\OrderRequest;
use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\RequestConversionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/** PRD-05 FIN - Konversi request yang disetujui menjadi order, hanya sekali (FIN-FR-004). */
class RequestConversionController extends Controller
{
    public function store(
        ConvertRequestRequest $request,
        OrderRequest $orderRequest,
        RequestConversionService $conversion,
    ): RedirectResponse {
        Gate::authorize('convert', $orderRequest);

        $dp = $request->validated('agreed_dp_amount');

        try {
            $order = $conversion->convert(
                $orderRequest,
                $request->user(),
                $dp === null ? null : (int) $dp,
                $request->validated('payment_terms_note'),
            );
        } catch (FinanceRuleException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Request berhasil dikonversi menjadi order '.$order->code.'.');
    }
}
