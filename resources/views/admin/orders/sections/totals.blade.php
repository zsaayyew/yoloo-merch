{{--
    PAGE-FIN-002 - Section 'totals' (Detail Order)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Nilai dibaca dari kolom orders. Perhitungan dilakukan service FIN, bukan di view.
    Status  : diimplementasikan (tampilan).
--}}
@use('App\Support\Money')

<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Ringkasan Biaya</h2>

    <dl class="ml-auto max-w-md space-y-2 text-sm">
        <div class="flex justify-between"><dt class="text-gray-600">Subtotal</dt><dd class="text-gray-900">{{ Money::format($order->subtotal) }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-600">Diskon</dt><dd class="text-gray-900">- {{ Money::format($order->discount) }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-600">Biaya tambahan</dt><dd class="text-gray-900">{{ Money::format($order->additional_fee) }}</dd></div>
        <div class="flex justify-between border-t border-gray-200 pt-2 text-base font-semibold">
            <dt class="text-gray-900">Total</dt><dd class="text-gray-900">{{ Money::format($order->total) }}</dd>
        </div>
        @if ($order->estimated_total !== null)
            <div class="flex justify-between"><dt class="text-gray-600">Estimasi awal</dt><dd class="text-gray-700">{{ Money::format($order->estimated_total) }}</dd></div>
        @endif
        @if ($order->agreed_dp_amount !== null)
            <div class="flex justify-between"><dt class="text-gray-600">DP yang disepakati</dt><dd class="text-gray-700">{{ Money::format($order->agreed_dp_amount) }}</dd></div>
        @endif
        @if ($order->payment_terms_note)
            <div class="pt-1 text-xs text-gray-500">{{ $order->payment_terms_note }}</div>
        @endif
    </dl>
</section>
