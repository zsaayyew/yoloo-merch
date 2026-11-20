{{--
    PAGE-CAT-001 - Katalog Produk
    Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
    Catatan : Route /produk.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Katalog Produk')

@section('content')
    @include('public.catalog.sections.search-filter')
    @include('public.catalog.sections.category-list')
    @include('public.catalog.sections.product-grid')
@endsection
