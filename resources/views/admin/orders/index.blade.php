{{--
    PAGE-FIN-001 - Daftar Order & Request
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Route /admin/orders. Tab "orders" dan "requests" lewat query ?tab=.
    Status  : diimplementasikan (hanya baca).
--}}
@extends('layouts.admin')

@section('title', 'Daftar Order & Request')

@section('content')
    @use('App\Support\Money')

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-semibold text-gray-900">Order &amp; Request</h1>
    </div>

    <div class="mb-4 flex gap-2 border-b border-gray-200">
        <a href="{{ route('admin.orders.index', ['tab' => 'orders']) }}"
            @class(['px-4 py-2 text-sm font-medium -mb-px border-b-2', 'border-gray-900 text-gray-900' => $tab === 'orders', 'border-transparent text-gray-500 hover:text-gray-800' => $tab !== 'orders'])>
            Order ({{ $orderCount }})
        </a>
        <a href="{{ route('admin.orders.index', ['tab' => 'requests']) }}"
            @class(['px-4 py-2 text-sm font-medium -mb-px border-b-2', 'border-gray-900 text-gray-900' => $tab === 'requests', 'border-transparent text-gray-500 hover:text-gray-800' => $tab !== 'requests'])>
            Custom Request ({{ $requestCount }})
            @if ($newRequestCount > 0)
                <span class="ml-1 rounded-full bg-blue-600 px-2 py-0.5 text-xs text-white">{{ $newRequestCount }} baru</span>
            @endif
        </a>
    </div>

    @include('admin.orders.sections.filters')

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
        @if ($tab === 'orders')
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Channel</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Pembayaran</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ $order->code }}</td>
                            <td class="px-4 py-3">
                                <div class="text-gray-900">{{ $order->customer?->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $order->customer?->whatsapp_number }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $order->type->label() }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $order->channel->label() }}</td>
                            <td class="px-4 py-3">@include('admin.orders.sections.status-pill', ['enum' => $order->status])</td>
                            <td class="px-4 py-3">@include('admin.orders.sections.status-pill', ['enum' => $order->payment_status])</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right text-gray-900">
                                @if ($order->total > 0)
                                    {{ Money::format($order->total) }}
                                @elseif ($order->estimated_total !== null)
                                    <span class="text-gray-500">Est. {{ Money::format($order->estimated_total) }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $order->ordered_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-blue-700 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-500">
                                Tidak ada order yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Kebutuhan</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                        <th class="px-4 py-3">Deadline</th>
                        <th class="px-4 py-3">Channel</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Diajukan</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($requests as $req)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ $req->code }}</td>
                            <td class="px-4 py-3">
                                <div class="text-gray-900">{{ $req->customer?->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $req->customer?->whatsapp_number }}</div>
                            </td>
                            <td class="max-w-xs px-4 py-3 text-gray-700">
                                @if ($req->product_name_snapshot)
                                    <div class="font-medium text-gray-900">{{ $req->product_name_snapshot }}</div>
                                @endif
                                <div class="truncate">{{ \Illuminate\Support\Str::limit($req->description, 70) }}</div>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-900">{{ number_format($req->quantity, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $req->desired_deadline?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $req->channel->label() }}</td>
                            <td class="px-4 py-3">@include('admin.orders.sections.status-pill', ['enum' => $req->status])</td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $req->submitted_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.requests.show', $req) }}" class="font-medium text-blue-700 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-500">
                                Tidak ada request yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-4">
        {{ ($tab === 'orders' ? $orders : $requests)->links() }}
    </div>
@endsection
