{{--
    PAGE-CP-002 - Tentang Kami
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Route /tentang-kami.
    Status  : profil dan panduan tersedia; visi, misi, nilai menunggu konten ADM.
--}}
@extends('layouts.public')

@section('title', 'Tentang Kami')
@section('meta_description', 'Kenali YOLOO Merch dan jelajahi panduan awal untuk melihat produk merchandise custom dan layanan printing.')

@section('content')
    @include('public.about.sections.profile')
    @include('public.about.sections.vision-mission')
    @include('public.about.sections.values-advantages')
    @include('public.about.sections.ordering-guide')
@endsection
