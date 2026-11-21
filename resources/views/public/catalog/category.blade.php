@extends('layouts.public')
@section('title', e($category->name) . ' · Katalog Produk')

@section('content')
    <div class="bg-[#FAF9F5] text-[#182F32]">
        <div class="mx-auto max-w-7xl px-4 pt-5 pb-14 sm:px-6 sm:pt-8 lg:px-8">
            @include('public.catalog.sections.category-header')
            @include('public.catalog.sections.category-list')
            @include('public.catalog.sections.search-filter')
            @include('public.catalog.sections.product-grid')
        </div>
    </div>
@endsection
