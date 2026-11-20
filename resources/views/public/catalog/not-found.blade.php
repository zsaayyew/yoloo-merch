{{--
    PAGE-CAT-005 - Produk Tidak Ditemukan
    Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
    Catatan : Slug tidak valid, draft, atau nonaktif mengembalikan HTTP 404.
    Status  : diimplementasikan.
--}}
@extends('layouts.public')
@section('title', ($loadFailed ?? false) ? 'Data Produk Gagal Dimuat' : ($heading ?? 'Produk Tidak Tersedia'))

@section('content')
    <section class="mx-auto max-w-4xl px-5 py-16 sm:px-8">
        @if ($loadFailed ?? false)
            <div role="alert">
                <h1 class="text-3xl font-bold">Data produk gagal dimuat</h1>
                <p class="mt-3 text-zinc-700">Silakan muat ulang halaman untuk mencoba lagi.</p>
                <a class="mt-4 inline-block font-semibold underline" href="{{ request()->fullUrl() }}">Coba lagi</a>
            </div>
        @else
            <h1 class="text-3xl font-bold">{{ $heading ?? 'Produk tidak tersedia' }}</h1>
            <p class="mt-3 text-zinc-700">Lihat pilihan lain di katalog YOLOO Merch.</p>
        @endif

        <a href="{{ route('catalog.index') }}" class="mt-5 inline-block rounded-lg bg-zinc-950 px-4 py-2 text-white">
            Kembali ke katalog
        </a>

        @if (isset($categories) && $categories->isNotEmpty())
            @include('public.catalog.sections.category-list', ['filters' => []])
        @endif
    </section>
@endsection