{{--
    PAGE-ADM-002 - Dashboard
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/dashboard. Data ringkasan dikirim DashboardController (ADM-FR-002).
              sales-summary diisi PRD-05 (subfitur Could) dan sengaja dibiarkan kosong.
--}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6 p-6">
        {{-- Kotak sambutan --}}
        <section class="rounded-lg border border-gray-200 bg-white p-6">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                {{ now()->locale('id')->translatedFormat('l, j F Y') }}
            </p>

            <h1 class="mt-1 text-2xl font-semibold text-gray-900">
                Selamat datang, {{ auth()->user()->name }}
            </h1>

            <p class="mt-2 max-w-2xl text-sm text-gray-600">
                Anda masuk sebagai
                <span class="font-medium text-gray-900">{{ auth()->user()->role?->label ?? 'Tanpa role' }}</span>.
                Dari dashboard ini Anda dapat mengelola konten website, katalog produk,
                konfigurasi harga, dan media.
                @if (auth()->user()->isOwner())
                    Sebagai Owner, Anda juga dapat mengelola akun Admin.
                @endif
            </p>

            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('admin.products.index') }}"
                   class="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                    Kelola Produk
                </a>
                <a href="{{ route('admin.content.homepage.edit') }}"
                   class="rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                    Konten Beranda
                </a>
                <a href="{{ route('admin.media.index') }}"
                   class="rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                    Media
                </a>
            </div>
        </section>

        @include('admin.dashboard.sections.content-summary')
        @include('admin.dashboard.sections.sales-summary')
    </div>
@endsection
