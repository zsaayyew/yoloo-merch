{{--
    FIN - Label status kecil (order, request, pembayaran).
    Pemakaian : @include('admin.orders.sections.status-pill', ['enum' => $order->status])
    Catatan   : sementara dipakai FIN agar tidak mengubah komponen shared x-ui.status-badge.
                Bisa diganti komponen itu setelah dikerjakan dan disepakati.
--}}
@php
    $pillColors = [
        'new' => 'bg-blue-100 text-blue-800',
        'under_review' => 'bg-indigo-100 text-indigo-800',
        'awaiting_info' => 'bg-yellow-100 text-yellow-800',
        'quotation_sent' => 'bg-purple-100 text-purple-800',
        'approved' => 'bg-green-100 text-green-800',
        'rejected' => 'bg-red-100 text-red-800',
        'expired' => 'bg-gray-200 text-gray-700',
        'converted' => 'bg-teal-100 text-teal-800',
        'awaiting_confirmation' => 'bg-yellow-100 text-yellow-800',
        'awaiting_payment' => 'bg-orange-100 text-orange-800',
        'processing' => 'bg-indigo-100 text-indigo-800',
        'ready_for_pickup' => 'bg-purple-100 text-purple-800',
        'completed' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800',
        'unpaid' => 'bg-red-100 text-red-800',
        'partially_paid' => 'bg-orange-100 text-orange-800',
        'paid' => 'bg-green-100 text-green-800',
    ];
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $pillColors[$enum->value] ?? 'bg-gray-100 text-gray-700' }}">
    {{ $enum->label() }}
</span>
