@extends('layouts.public')
@section('title', 'Katalog Produk')

@section('content')
    <div class="bg-[#FAF9F5] text-[#182F32]">
        <div class="mx-auto max-w-7xl px-4 pt-5 pb-14 sm:px-6 sm:pt-8 lg:px-8">
            @include('public.catalog.sections.category-header', ['category' => null])

            @if ($loadFailed ?? false)
                <section role="alert" class="mt-6 rounded-2xl border border-[#DDDCD4] border-l-4 border-l-[#E97E37] bg-white p-6 sm:p-8">
                    <h2 class="text-xl font-bold">Katalog sedang tidak dapat dimuat.</h2>
                    <a class="mt-4 inline-flex min-h-11 items-center rounded-lg bg-[#5AC5CB] px-5 font-bold text-[#182F32] hover:bg-[#82D4D7] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#17666B]"
                       href="{{ request()->fullUrl() }}">Coba lagi</a>
                </section>
            @else
                @include('public.catalog.sections.category-list')
                @include('public.catalog.sections.search-filter')
                @include('public.catalog.sections.product-grid')
            @endif
        </div>
    </div>
@endsection
