{{--
    PAGE-FIN-002 - Section 'quotation' (Detail Request)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Hanya menampilkan penawaran. Form isi penawaran menyusul (QuotationController).
    Status  : diimplementasikan (tampilan).
--}}
<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Penawaran</h2>

    @if ($orderRequest->quotation_amount === null)
        <p class="text-sm text-gray-500">Penawaran belum dibuat.</p>
    @else
        <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-gray-500">Nominal penawaran</dt>
                <dd class="font-medium text-gray-900">{{ \App\Support\Money::format($orderRequest->quotation_amount) }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Syarat penawaran</dt>
                <dd class="whitespace-pre-line text-gray-900">{{ $orderRequest->quotation_terms ?? '-' }}</dd>
            </div>
        </dl>
    @endif
</section>
