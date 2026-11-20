{{--
    PAGE-CAT-003 - Detail Produk
    Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
    Catatan : Route /produk/{slug}. Panel kalkulator adalah PAGE-CAT-004.
    Status  : diimplementasikan.
--}}
@extends('layouts.public')
@section('title', e($product->name) . ' · Katalog Produk')

@section('content')
    <div class="mx-auto max-w-7xl px-5 py-10 sm:px-8">
        <nav aria-label="Lokasi halaman" class="mb-6 flex flex-wrap items-center gap-2 text-sm text-zinc-700">
            <a class="underline" href="{{ route('catalog.index') }}">Katalog</a>
            @if ($category)
                <span aria-hidden="true">/</span>
                <a class="underline" href="{{ route('catalog.category', ['category' => $category->slug]) }}">{{ $category->name }}</a>
            @endif
        </nav>

        <div class="grid items-start gap-8 lg:grid-cols-2 lg:gap-12">
            @include('public.catalog.sections.product-gallery')
            <div class="space-y-6">
                @include('public.catalog.sections.product-info')
                @include('public.catalog.sections.price-info')
                @include('public.catalog.sections.configurator')
            </div>
        </div>

        @include('public.catalog.sections.related-products')
    </div>
@endsection