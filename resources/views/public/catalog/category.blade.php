{{--
    PAGE-CAT-002 - Produk per Kategori
    Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
    Catatan : Route /produk/kategori/{slug}.
    Status  : diimplementasikan.
--}}
@extends('layouts.public')
@section('title', e($category->name) . ' · Katalog Produk')

@section('content')
    <div class="mx-auto max-w-7xl px-5 py-10 sm:px-8">
        @include('public.catalog.sections.category-header')
        @include('public.catalog.sections.search-filter')
        @include('public.catalog.sections.product-grid')
    </div>
@endsection