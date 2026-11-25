{{--
    PAGE-ORD-006 - Konfirmasi Custom Order Berhasil Dikirim
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Hanya bisa dibuka oleh sesi pengirim. Tidak ada QRIS, instruksi pembayaran,
              atau tautan cek status (ORD-BR-013).
              Tombol WhatsApp (ORD-FR-016) tampil hanya jika nomor WhatsApp usaha sudah diatur ADM.
    Status  : diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Permintaan Terkirim')

@section('content')
    <section class="mx-auto max-w-2xl px-4 py-10 sm:py-14">
        <div class="text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-2xl text-green-700">✓</div>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">Permintaan berhasil dikirim</h1>
            <p class="mt-2 text-gray-600">Simpan kode di bawah ini untuk disebutkan saat menghubungi admin.</p>
        </div>

        @include('public.custom-orders.sections.confirmation-details')

        <div class="mt-8 rounded-xl border border-gray-200 p-5">
            <h2 class="font-semibold text-gray-900">Langkah berikutnya</h2>
            <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-gray-700">
                <li>Admin YOLOO Merch akan meninjau permintaanmu.</li>
                <li>Admin menghubungimu lewat WhatsApp untuk konfirmasi detail dan penawaran harga.</li>
                <li>Setelah kamu menyetujui penawaran, admin akan memproses pesananmu.</li>
            </ol>

            @if ($whatsappUrl)
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                    class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700">
                    Hubungi Admin via WhatsApp
                </a>
                <p class="mt-2 text-xs text-gray-500">Pesan otomatis berisi kode {{ $orderRequest->code }} dan namamu.</p>
            @endif
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
