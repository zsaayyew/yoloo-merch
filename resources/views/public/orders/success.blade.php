{{--
    PAGE-ORD-004 - Konfirmasi Pesanan Diterima
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : ORD-FR-009. Hanya sesi pengirim. Kode, ringkasan, status Menunggu Konfirmasi,
              info pickup, admin akan menghubungi via WhatsApp. TANPA instruksi pembayaran/QRIS
              dan tanpa tautan cek status (ORD-BR-013).
    Status  : diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Pesanan Terkirim')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-10 sm:py-14">
        <div class="text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-2xl text-green-700">✓</div>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">Pesanan berhasil dikirim</h1>
            <p class="mt-2 text-gray-600">Simpan kode pesanan ini. Tunjukkan atau sebutkan saat menghubungi admin dan saat mengambil pesanan.</p>
        </div>

        @include('public.orders.sections.confirmation-details')

        <div class="mt-6 grid gap-6 sm:grid-cols-2">
            <x-ordering.pickup-info />
            @include('public.orders.sections.next-steps')
        </div>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('catalog.index') }}"
                class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-6 py-3 font-semibold text-white hover:bg-gray-700">
                Lihat katalog
            </a>
            <a href="{{ route('home') }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 font-semibold text-gray-900 hover:bg-gray-50">
                Kembali ke beranda
            </a>
        </div>
    </section>
@endsection
