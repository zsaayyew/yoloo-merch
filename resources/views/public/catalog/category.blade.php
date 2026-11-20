{{--
    PAGE-CAT-002 - Produk per Kategori
    Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
    Catatan : Route /produk/kategori/{slug}.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Produk per Kategori')

@section('content')
    @include('public.catalog.sections.category-header')
    @include('public.catalog.sections.search-filter')
    @include('public.catalog.sections.product-grid')
@endsection
