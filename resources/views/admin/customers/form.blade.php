{{--
    PAGE-FIN-004 - Form Pelanggan (tambah / ubah)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Nomor WhatsApp dinormalisasi ke 62xxxxxxxxxx di CustomerRequest (FIN-BR-012).
              Pelanggan tidak dihapus, hanya dinonaktifkan.
    Status  : diimplementasikan.
--}}
@extends('layouts.admin')

@php $isEdit = $customer->exists; @endphp

@section('title', $isEdit ? 'Ubah Pelanggan' : 'Tambah Pelanggan')

@section('content')
    <div class="mb-4">
        <a href="{{ $isEdit ? route('admin.customers.show', $customer) : route('admin.customers.index') }}" class="text-sm text-gray-500 hover:text-gray-800">&larr; Kembali</a>
        <h1 class="mt-1 text-xl font-semibold text-gray-900">{{ $isEdit ? 'Ubah Pelanggan' : 'Tambah Pelanggan' }}</h1>
    </div>

    <form method="POST" action="{{ $isEdit ? route('admin.customers.update', $customer) : route('admin.customers.store') }}"
        class="max-w-2xl space-y-4 rounded-xl border border-gray-200 bg-white p-6">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nama <span class="text-red-600">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required maxlength="100"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="whatsapp_number" class="mb-1 block text-sm font-medium text-gray-700">Nomor WhatsApp <span class="text-red-600">*</span></label>
            <input type="text" id="whatsapp_number" name="whatsapp_number" inputmode="tel" required
                value="{{ old('whatsapp_number', $customer->whatsapp_number) }}" placeholder="0812-3456-7890"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <p class="mt-1 text-xs text-gray-500">Disimpan dalam format 62xxxxxxxxxx. Dipakai untuk mengenali pelanggan yang sama.</p>
            @error('whatsapp_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}" maxlength="255"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="notes" class="mb-1 block text-sm font-medium text-gray-700">Catatan internal</label>
            <textarea id="notes" name="notes" rows="4" maxlength="2000"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('notes', $customer->notes) }}</textarea>
            @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $customer->is_active ?? true)) class="rounded border-gray-300">
            <label for="is_active" class="text-sm text-gray-700">Pelanggan aktif</label>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="rounded-lg bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-700">Simpan</button>
            <a href="{{ $isEdit ? route('admin.customers.show', $customer) : route('admin.customers.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
        </div>
    </form>
@endsection
