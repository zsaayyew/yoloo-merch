{{--
    PAGE-FIN-002 - Detail Order / Request
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Satu view untuk dua route: admin.orders.show ($order terisi) dan
              admin.requests.show ($orderRequest terisi). payments + refunds = PAGE-FIN-005.
              Modal aksi ada di admin/orders/modals.
    Status  : bagian info pelanggan, item, lampiran, status (+ubah status request), penawaran, konversi, dan total sudah diimplementasikan;
              bagian riwayat status, pembayaran, refund, invoice, catatan internal masih placeholder.
--}}
@extends('layouts.admin')

@section('title', $order ? 'Order '.$order->code : 'Request '.$orderRequest->code)

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('admin.orders.index', ['tab' => $order ? 'orders' : 'requests']) }}"
                class="text-sm text-gray-500 hover:text-gray-800">&larr; Kembali ke daftar</a>
            <h1 class="mt-1 text-xl font-semibold text-gray-900">
                {{ $order ? 'Order' : 'Custom Request' }} {{ $order?->code ?? $orderRequest->code }}
            </h1>
        </div>
        <div class="flex items-center gap-2">
            @if ($order)
                @include('admin.orders.sections.status-pill', ['enum' => $order->status])
                @include('admin.orders.sections.status-pill', ['enum' => $order->payment_status])
            @else
                @include('admin.orders.sections.status-pill', ['enum' => $orderRequest->status])
            @endif
        </div>
    </div>

    @if (session('success'))
        <p class="mb-4 rounded-lg bg-green-50 px-4 py-2 text-sm text-green-800" role="status">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p class="mb-4 rounded-lg bg-red-50 px-4 py-2 text-sm text-red-800" role="alert">{{ session('error') }}</p>
    @endif

    <div class="space-y-4">
        @include('admin.orders.sections.customer-info')
        @include('admin.orders.sections.items')
        @include('admin.orders.sections.attachments')

        @if ($orderRequest)
            @include('admin.orders.sections.request-status')
            @include('admin.orders.sections.quotation')
            @include('admin.orders.modals.convert-request')
        @endif

        @if ($order)
            @include('admin.orders.sections.order-status')
            @include('admin.orders.sections.totals')
            @include('admin.orders.sections.status-history')
            @include('admin.orders.sections.payments')
            @include('admin.orders.sections.refunds')
            @include('admin.orders.sections.invoices')
        @endif

        @include('admin.orders.sections.internal-notes')
    </div>
@endsection
