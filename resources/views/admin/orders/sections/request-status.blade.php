{{--
    PAGE-FIN-002 - Section 'request-status' (Detail Request)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Hanya menampilkan status. Tombol ubah status dan konversi
              menyusul bersama RequestStatusController dan RequestConversionController.
    Status  : diimplementasikan (tampilan).
--}}
<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Status Request</h2>

    <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">
        <div>
            <dt class="text-gray-500">Status</dt>
            <dd class="mt-1">@include('admin.orders.sections.status-pill', ['enum' => $orderRequest->status])</dd>
        </div>
        <div>
            <dt class="text-gray-500">Diajukan</dt>
            <dd class="font-medium text-gray-900">{{ $orderRequest->submitted_at?->format('d/m/Y H:i') }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Order hasil konversi</dt>
            <dd class="font-medium text-gray-900">
                @if ($orderRequest->convertedOrder)
                    <a href="{{ route('admin.orders.show', $orderRequest->convertedOrder) }}" class="text-blue-700 hover:underline">{{ $orderRequest->convertedOrder->code }}</a>
                @else
                    Belum dikonversi
                @endif
            </dd>
        </div>
    </dl>
</section>
