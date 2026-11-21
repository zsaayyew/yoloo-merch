{{--
    PAGE-CAT-003 - Detail Produk
    Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
    Catatan : Route /produk/{slug}. Panel kalkulator adalah PAGE-CAT-004.
    Status  : diimplementasikan.
--}}
@extends('layouts.public')
@section('title', e($product->name) . ' · Katalog Produk')

@section('content')
    <div class="bg-[#FAF9F5] text-[#182F32] [overflow-wrap:anywhere] [&_:focus-visible]:outline-2 [&_:focus-visible]:outline-offset-4 [&_:focus-visible]:outline-[#14676c]">
      <div class="mx-auto max-w-7xl px-4 pb-16 sm:px-8 lg:px-12">
        <nav aria-label="Lokasi halaman" class="flex flex-wrap items-center gap-x-2 gap-y-1 py-5 text-sm font-medium text-[#456063] sm:py-7">
            <a class="underline decoration-[#5AC5CB] underline-offset-4 hover:text-[#182F32]" href="{{ route('catalog.index') }}">Katalog</a>
            @if ($category)
                <span aria-hidden="true">/</span>
                <a class="underline decoration-[#5AC5CB] underline-offset-4 hover:text-[#182F32]" href="{{ route('catalog.category', ['category' => $category->slug]) }}">{{ $category->name }}</a>
            @endif
            <span aria-hidden="true">/</span>
            <span aria-current="page" class="text-[#182F32]">{{ $product->name }}</span>
        </nav>

        <div class="grid min-w-0 items-start gap-7 lg:grid-cols-2 lg:gap-12 xl:gap-16">
            <div class="min-w-0">
                @include('public.catalog.sections.product-gallery')
            </div>
            <div class="min-w-0 space-y-6">
                @include('public.catalog.sections.product-info')
                @include('public.catalog.sections.price-info')
                @include('public.catalog.sections.configurator')
            </div>
        </div>

        @include('public.catalog.sections.related-products')
      </div>
    </div>
@endsection
