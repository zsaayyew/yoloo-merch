{{--
    PAGE-FIN-002 - Section 'order-status' (Detail Order)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Hanya menampilkan status. Tombol ubah status dan batalkan menyusul
              (OrderStatusController, OrderCancellationController).
    Status  : diimplementasikan (tampilan).
--}}
<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Status Order</h2>

    <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <dt class="text-gray-500">Status</dt>
            <dd class="mt-1">@include('admin.orders.sections.status-pill', ['enum' => $order->status])</dd>
        </div>
        <div>
            <dt class="text-gray-500">Pembayaran</dt>
            <dd class="mt-1">@include('admin.orders.sections.status-pill', ['enum' => $order->payment_status])</dd>
        </div>
        <div>
            <dt class="text-gray-500">Tipe</dt>
            <dd class="font-medium text-gray-900">{{ $order->type->label() }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Pengambilan</dt>
            <dd class="font-medium text-gray-900">{{ $order->fulfillment === 'pickup' ? 'Ambil di tempat' : $order->fulfillment }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Tanggal order</dt>
            <dd class="font-medium text-gray-900">{{ $order->ordered_at?->format('d/m/Y H:i') }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Asal request</dt>
            <dd class="font-medium text-gray-900">
                @if ($order->sourceRequest)
                    <a href="{{ route('admin.requests.show', $order->sourceRequest) }}" class="text-blue-700 hover:underline">{{ $order->sourceRequest->code }}</a>
                @else
                    -
                @endif
            </dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-gray-500">Catatan order</dt>
            <dd class="whitespace-pre-line text-gray-900">{{ $order->notes ?? '-' }}</dd>
        </div>
        @if ($order->cancelled_at)
            <div class="sm:col-span-2 lg:col-span-4 rounded-lg bg-red-50 p-3">
                <dt class="text-red-700">Dibatalkan pada {{ $order->cancelled_at->format('d/m/Y H:i') }}</dt>
                <dd class="whitespace-pre-line text-red-900">{{ $order->cancellation_reason ?? '-' }}</dd>
            </div>
        @endif
    </dl>
</section>
