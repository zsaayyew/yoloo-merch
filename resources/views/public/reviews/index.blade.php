{{--
    PAGE-CP-003 - Ulasan & Kolaborasi
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Route /ulasan-kolaborasi.
    Status  : empty state tersedia; data publik menunggu ADM.
--}}
@extends('layouts.public')

@section('title', 'Ulasan & Kolaborasi')
@section('meta_description', 'Ulasan pelanggan dan dokumentasi kolaborasi YOLOO Merch yang telah dikurasi dan dipublikasikan.')

@section('content')
    <section class="bg-[#181818] text-white">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-16 lg:px-8">
            <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#8ce0e4]">Cerita &amp; karya</p>
            <h1 class="mt-3 text-4xl font-black tracking-tight sm:text-5xl">Ulasan &amp; Kolaborasi</h1>
            <p class="mt-4 max-w-2xl leading-7 text-white/75">Ulasan pelanggan dan hasil kolaborasi yang telah disetujui untuk dipublikasikan.</p>
            <nav class="mt-6 flex flex-wrap gap-x-5 gap-y-2 text-sm font-bold" aria-label="Bagian halaman">
                <a href="#testimonials" class="underline decoration-[#58C0C8] decoration-2 underline-offset-4">Ulasan pelanggan</a>
                <a href="#collaborations" class="underline decoration-[#E87830] decoration-2 underline-offset-4">Kolaborasi</a>
            </nav>
        </div>
    </section>
    @include('public.reviews.sections.testimonials')
    @include('public.reviews.sections.collaborations')
@endsection
