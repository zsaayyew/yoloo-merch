{{--
    PAGE-FIN-004 - Daftar Pelanggan
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Route /admin/customers.
    Status  : diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Daftar Pelanggan')

@section('content')
    @use('App\Support\Money')

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-semibold text-gray-900">Pelanggan</h1>
        <a href="{{ route('admin.customers.create') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Tambah Pelanggan</a>
    </div>

    @if (session('success'))
        <p class="mb-4 rounded-lg bg-green-50 px-4 py-2 text-sm text-green-800" role="status">{{ session('success') }}</p>
    @endif

    <form method="GET" action="{{ route('admin.customers.index') }}"
        class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-gray-200 bg-white p-4 sm:grid-cols-4">
        <div class="sm:col-span-2">
            <label for="q" class="mb-1 block text-xs font-medium text-gray-600">Cari</label>
            <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama, email, atau nomor WhatsApp"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label for="status" class="mb-1 block text-xs font-medium text-gray-600">Status</label>
            <select id="status" name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua</option>
                <option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option>
                <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Nonaktif</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Terapkan</button>
            <a href="{{ route('admin.customers.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset</a>
        </div>
        @if ($errors->any())
            <p class="text-sm text-red-600 sm:col-span-4" role="alert">{{ $errors->first() }}</p>
        @endif
    </form>

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">WhatsApp</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3 text-right">Order</th>
                    <th class="px-4 py-3 text-right">Request</th>
                    <th class="px-4 py-3 text-right">Total transaksi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $customer->name }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $customer->whatsapp_number }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $customer->email ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">{{ $customer->orders_count }}</td>
                        <td class="px-4 py-3 text-right">{{ $customer->order_requests_count }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">{{ Money::format((int) $customer->total_spent) }}</td>
                        <td class="px-4 py-3">
                            <span @class(['inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium', 'bg-green-100 text-green-800' => $customer->is_active, 'bg-gray-200 text-gray-700' => ! $customer->is_active])>
                                {{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="font-medium text-blue-700 hover:underline">Detail</a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="ml-3 font-medium text-gray-700 hover:underline">Ubah</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-10 text-center text-gray-500">Belum ada pelanggan yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
@endsection
