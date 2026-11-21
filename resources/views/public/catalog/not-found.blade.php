@extends('layouts.public')
@section('title', ($loadFailed ?? false) ? 'Data Produk Gagal Dimuat' : ($heading ?? 'Produk Tidak Tersedia'))

@section('content')
    <div class="bg-[#FAF9F5] px-4 py-9 text-[#182F32] sm:px-6 sm:py-16">
        <section class="mx-auto max-w-4xl rounded-3xl border border-[#D2DCD7] bg-white p-6 sm:p-10">
            <img src="{{ asset('images/brand/yoloo-symbol.png') }}" alt="" width="468" height="426" class="mb-5 h-14 w-auto">
            @if ($loadFailed ?? false)
                <div role="alert">
                    <h1 class="text-3xl font-black tracking-tight sm:text-4xl">Data produk gagal dimuat</h1>
                    <p class="mt-3 text-[#465C5D]">Silakan muat ulang halaman untuk mencoba lagi.</p>
                </div>
            @else
                <h1 class="text-3xl font-black tracking-tight sm:text-4xl">{{ $heading ?? 'Produk tidak tersedia' }}</h1>
                <p class="mt-3 text-[#465C5D]">Lihat pilihan lain di katalog YOLOO Merch.</p>
            @endif
            <div class="mt-6 flex flex-wrap gap-3">
                @if ($loadFailed ?? false)
                    <a class="inline-flex min-h-11 items-center rounded-lg bg-[#5AC5CB] px-5 text-sm font-bold hover:bg-[#82D4D7] focus-visible:outline-2 focus-visible:outline-offset-3" href="{{ request()->fullUrl() }}">Coba lagi</a>
                @endif
                <a href="{{ route('catalog.index') }}" class="inline-flex min-h-11 items-center rounded-lg bg-[#182F32] px-5 text-sm font-bold text-white hover:bg-[#235459] focus-visible:outline-2 focus-visible:outline-offset-3 focus-visible:outline-[#17666B]">
                    Kembali ke katalog
                </a>
            </div>
            @if (isset($categories) && $categories->isNotEmpty())
                @include('public.catalog.sections.category-list', ['filters' => []])
            @endif
        </section>
    </div>
@endsection
