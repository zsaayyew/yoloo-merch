{{--
    PAGE-ORD-005 - Formulir Custom Order Request
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : ORD-FR-010, ORD-FR-012, ORD-FR-013, ORD-FR-017. Tidak ada harga final,
              QRIS, atau instruksi pembayaran di halaman ini.
    Status  : diimplementasikan (unggah file referensi menyusul di ORD-FR-011).
--}}
@extends('layouts.public')

@section('title', 'Custom Order')

@section('content')
    @php
        $inputClass = 'mt-1 block w-full rounded-lg border bg-white px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/20';
    @endphp

    <section class="mx-auto max-w-3xl px-4 py-10 sm:py-14">
        <header class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Custom Order</p>
            <h1 class="mt-1 text-3xl font-bold text-gray-900 sm:text-4xl">Ajukan Permintaan Custom</h1>
            <p class="mt-3 text-gray-600">
                Ceritakan kebutuhanmu. Admin YOLOO Merch akan meninjau permintaan ini, lalu menghubungimu
                lewat WhatsApp untuk penawaran harga. Pengajuan ini <strong>belum menjadi pesanan</strong>
                dan belum perlu dibayar.
            </p>
        </header>

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                <p class="font-semibold">Ada isian yang perlu diperbaiki.</p>
                @error('submission_token')
                    <p class="mt-1">{{ $message }}</p>
                @enderror
                @error('website')
                    <p class="mt-1">{{ $message }}</p>
                @enderror
            </div>
        @endif

        <form method="POST" action="{{ route('custom-orders.store') }}" data-submit-guard class="space-y-8">
            @csrf
            <input type="hidden" name="submission_token" value="{{ $submissionToken }}">

            {{-- Honeypot anti-spam (ORD-FR-017): disembunyikan dari pengguna, diisi oleh bot. --}}
            <div class="hidden" aria-hidden="true">
                <label for="website">Jangan diisi</label>
                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            @include('public.custom-orders.sections.product-reference')
            @include('public.custom-orders.sections.requirements')
            {{-- @include('public.custom-orders.sections.reference-files')  -- ORD-FR-011, menyusul --}}
            @include('public.custom-orders.sections.contact-form')
            @include('public.custom-orders.sections.agreement')

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('catalog.index') }}" class="text-center text-sm font-medium text-gray-600 hover:text-gray-900">
                    Kembali ke katalog
                </a>
                <button type="submit" data-loading-text="Mengirim..."
                    class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-6 py-3 font-semibold text-white shadow-sm hover:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-60">
                    Kirim Permintaan
                </button>
            </div>
        </form>
    </section>
@endsection
