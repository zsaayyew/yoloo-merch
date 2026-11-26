{{--
    PAGE-FIN-004 - Detail Pelanggan
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Menampilkan 20 order dan 20 request terbaru milik pelanggan.
    Status  : diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Detail Pelanggan')

@section('content')
    @use('App\Support\Money')

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-500 hover:text-gray-800">&larr; Kembali ke daftar</a>
            <h1 class="mt-1 text-xl font-semibold text-gray-900">{{ $customer->name }}</h1>
        </div>
        <a href="{{ route('admin.customers.edit', $customer) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Ubah Data</a>
    </div>

    @if (session('success'))
        <p class="mb-4 rounded-lg bg-green-50 px-4 py-2 text-sm text-green-800" role="status">{{ session('success') }}</p>
    @endif

    <div class="space-y-4">
        <section class="rounded-xl border border-gray-200 bg-white p-4">
            <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Data Pelanggan</h2>
            <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <dt class="text-gray-500">WhatsApp</dt>
                    <dd class="font-medium"><a href="https://wa.me/{{ $customer->whatsapp_number }}" target="_blank" rel="noopener" class="text-blue-700 hover:underline">{{ $customer->whatsapp_number }}</a></dd>
                </div>
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium text-gray-900">{{ $customer->email ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Status</dt>
                    <dd class="font-medium text-gray-900">{{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Terdaftar</dt>
                    <dd class="font-medium text-gray-900">{{ $customer->created_at?->format('d/m/Y') }}</dd>
                </div>
                <div class="sm:col-span-2 lg:col-span-4">
                    <dt class="text-gray-500">Catatan</dt>
                    <dd class="whitespace-pre-line text-gray-900">{{ $customer->notes ?? '-' }}</dd>
                </div>
            </dl>
        </section>

        <section class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <div class="text-xs uppercase text-gray-500">Total order</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['orders'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <div class="text-xs uppercase text-gray-500">Total request</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['requests'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <div class="text-xs uppercase text-gray-500">Total transaksi (tanpa batal)</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900">{{ Money::format($stats['spent']) }}</div>
            </div>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-4">
            <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Riwayat Order</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="text-left text-xs font-semibold uppercase text-gray-500">
                        <tr>
                            <th class="py-2 pr-4">Kode</th><th class="py-2 pr-4">Status</th><th class="py-2 pr-4">Pembayaran</th>
                            <th class="py-2 pr-4 text-right">Total</th><th class="py-2 pr-4">Tanggal</th><th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="py-2 pr-4 font-medium text-gray-900">{{ $order->code }}</td>
                                <td class="py-2 pr-4">@include('admin.orders.sections.status-pill', ['enum' => $order->status])</td>
                                <td class="py-2 pr-4">@include('admin.orders.sections.status-pill', ['enum' => $order->payment_status])</td>
                                <td class="whitespace-nowrap py-2 pr-4 text-right">{{ Money::format($order->total) }}</td>
                                <td class="whitespace-nowrap py-2 pr-4 text-gray-700">{{ $order->ordered_at?->format('d/m/Y') }}</td>
                                <td class="py-2 text-right"><a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-blue-700 hover:underline">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-6 text-center text-gray-500">Belum ada order.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-4">
            <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Riwayat Custom Request</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="text-left text-xs font-semibold uppercase text-gray-500">
                        <tr>
                            <th class="py-2 pr-4">Kode</th><th class="py-2 pr-4">Kebutuhan</th><th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4">Diajukan</th><th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($requests as $req)
                            <tr>
                                <td class="py-2 pr-4 font-medium text-gray-900">{{ $req->code }}</td>
                                <td class="max-w-xs truncate py-2 pr-4 text-gray-700">{{ \Illuminate\Support\Str::limit($req->description, 60) }}</td>
                                <td class="py-2 pr-4">@include('admin.orders.sections.status-pill', ['enum' => $req->status])</td>
                                <td class="whitespace-nowrap py-2 pr-4 text-gray-700">{{ $req->submitted_at?->format('d/m/Y') }}</td>
                                <td class="py-2 text-right"><a href="{{ route('admin.requests.show', $req) }}" class="font-medium text-blue-700 hover:underline">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-gray-500">Belum ada request.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
