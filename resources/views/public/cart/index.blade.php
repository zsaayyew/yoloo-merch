{{--
    PAGE-ORD-001 - Keranjang
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : ORD-FR-001 s.d. 004. Semua angka berlabel Estimasi dan dihitung ulang di server.
              Tidak ada QRIS, instruksi pembayaran, alamat, atau ongkir (pickup saja).
    Status  : diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Keranjang')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-10 sm:py-14">
        <header class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Keranjang</h1>
            <p class="mt-2 text-gray-600">Periksa produk pilihanmu sebelum melanjutkan ke checkout.</p>
        </header>

        @if (session('status'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif

        @if ($rows->isEmpty())
            <div class="rounded-xl border border-dashed border-gray-300 px-6 py-14 text-center">
                <p class="text-lg font-semibold text-gray-900">Keranjang masih kosong</p>
                <p class="mt-2 text-gray-600">Pilih produk dari katalog, atau ajukan custom order jika kebutuhanmu khusus.</p>
                <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ route('catalog.index') }}"
                        class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-6 py-3 font-semibold text-white hover:bg-gray-700">
                        Lihat katalog
                    </a>
                    <a href="{{ route('custom-orders.create') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 font-semibold text-gray-900 hover:bg-gray-50">
                        Ajukan custom order
                    </a>
                </div>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    @include('public.cart.sections.item-list')
                </div>
                <div>
                    @include('public.cart.sections.totals')
                </div>
            </div>
        @endif
    </section>
@endsection
