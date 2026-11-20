{{--
    PAGE-CAT-003 - Detail Produk
    Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
    Catatan : Route /produk/{slug}. calculator-panel = PAGE-CAT-004 (hanya untuk model kalkulator).
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Detail Produk')

@section('content')
    @include('public.catalog.sections.product-gallery')
    @include('public.catalog.sections.product-info')
    @include('public.catalog.sections.price-info')
    @include('public.catalog.sections.configurator')
    @include('public.catalog.sections.calculator-panel')
    @include('public.catalog.sections.order-actions')
    @include('public.catalog.sections.related-products')
@endsection
