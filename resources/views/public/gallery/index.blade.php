{{--
    PAGE-CP-004 - Galeri
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Route /galeri. Menggunakan lightbox khusus halaman CP.
    Status  : grid/filter siap untuk kontrak ADM; saat ini menampilkan empty state.
--}}
@extends('layouts.public')

@section('title', 'Galeri')
@section('meta_description', 'Lihat hasil produksi YOLOO Merch yang telah dipublikasikan dan jelajahi galeri berdasarkan kategori.')

@section('content')
    @include('public.gallery.sections.filter')
    @include('public.gallery.sections.grid')
@endsection
