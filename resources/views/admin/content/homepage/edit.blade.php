{{--
    PAGE-ADM-003 - Konten Beranda
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/content/homepage (ADM-FR-003).
--}}
@extends('layouts.admin')

@section('title', 'Konten Beranda')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Konten Beranda"
            description="Sunting teks dan gambar tiap section beranda. Section yang tidak dicentang tidak ditampilkan di website.">
            <x-slot:actions>
                <a href="{{ route('home') }}" target="_blank" rel="noopener"
                   class="inline-flex min-h-10 items-center justify-center rounded-full border-2 border-[#181818] px-4 py-2 text-sm font-bold text-[#181818] transition hover:bg-[#58C0C8]/20">
                    Lihat website
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="POST" action="{{ route('admin.content.homepage.update') }}"
              enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            @include('admin.content.partials.sections-form')

            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-6 py-2.5 text-sm font-bold text-white transition hover:bg-[#333] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    Simpan Konten Beranda
                </button>
            </div>
        </form>
    </div>
@endsection
