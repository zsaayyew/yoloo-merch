{{--
    PAGE-ADM-003 - Konten Tentang Kami
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/content/about (ADM-FR-003).
--}}
@extends('layouts.admin')

@section('title', 'Konten Tentang Kami')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Konten Tentang Kami"
            description="Sunting profil usaha, visi misi, keunggulan, dan panduan pemesanan pada halaman tentang kami.">
            <x-slot:actions>
                <a href="{{ route('about') }}" target="_blank" rel="noopener"
                   class="inline-flex min-h-10 items-center justify-center rounded-full border-2 border-[#181818] px-4 py-2 text-sm font-bold text-[#181818] transition hover:bg-[#58C0C8]/20">
                    Lihat website
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="POST" action="{{ route('admin.content.about.update') }}"
              enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            @include('admin.content.partials.sections-form')

            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-6 py-2.5 text-sm font-bold text-white transition hover:bg-[#333] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    Simpan Konten Tentang
                </button>
            </div>
        </form>
    </div>
@endsection
