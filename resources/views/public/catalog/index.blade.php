{{--
    PAGE-CAT-001 - Katalog Produk
    Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
    Catatan : Route /produk.
    Status  : diimplementasikan.
--}}
@extends('layouts.public')
@section('title', 'Katalog Produk')

@section('content')
    <div class="mx-auto max-w-7xl px-5 py-10 sm:px-8">
        <h1 class="text-4xl font-bold tracking-tight text-zinc-950">Katalog Produk</h1>
        @if ($loadFailed ?? false)
            <div role="alert" class="mt-6 rounded-xl bg-red-50 p-5 text-red-950">
                <p>Katalog sedang tidak dapat dimuat.</p>
                <a class="mt-2 inline-block font-semibold underline" href="{{ request()->fullUrl() }}">Coba lagi</a>
            </div>
        @else
            @include('public.catalog.sections.category-list')
            @include('public.catalog.sections.search-filter')
            @include('public.catalog.sections.product-grid')
        @endif
    </div>
@endsection