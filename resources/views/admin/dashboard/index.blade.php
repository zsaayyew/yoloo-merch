{{--
    PAGE-ADM-002 - Dashboard
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/dashboard. Data ringkasan dikirim DashboardController (ADM-FR-002).
              sales-summary diisi PRD-05 (subfitur Could) dan sengaja dibiarkan kosong.
              Warna mengikuti palet website publik (lihat resources/views/public).
--}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        {{-- Kotak sambutan --}}
        <section class="rounded-3xl border-2 border-[#181818] bg-white p-6 shadow-[6px_6px_0_#181818] sm:p-8">
            <p class="inline-flex rounded-full bg-[#E87830]/20 px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-[#181818]">
                {{ now()->locale('id')->translatedFormat('l, j F Y') }}
            </p>

            <h1 class="mt-4 text-3xl font-black leading-tight tracking-tight text-[#181818] sm:text-4xl">
                Selamat datang, {{ auth()->user()->name }}
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-7 text-[#181818]/70">
                Anda masuk sebagai
                <span class="font-bold text-[#124f53]">{{ auth()->user()->role?->label ?? 'Tanpa role' }}</span>.
                Dari dashboard ini Anda dapat mengelola konten website, katalog produk,
                konfigurasi harga, dan media.
                @if (auth()->user()->isOwner())
                    Sebagai Owner, Anda juga dapat mengelola akun Admin.
                @endif
            </p>

            <div class="mt-5 flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                <a href="{{ route('admin.products.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-[#333] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    Kelola Produk
                </a>
                <a href="{{ route('admin.content.homepage.edit') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-full border-2 border-[#181818] px-5 py-2.5 text-sm font-bold text-[#181818] transition hover:bg-[#58C0C8]/20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    Konten Beranda
                </a>
                <a href="{{ route('admin.media.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-full border-2 border-[#181818] px-5 py-2.5 text-sm font-bold text-[#181818] transition hover:bg-[#58C0C8]/20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    Media
                </a>
            </div>
        </section>

        @include('admin.dashboard.sections.content-summary')
        @include('admin.dashboard.sections.sales-summary')
    </div>
@endsection
