{{--
    PAGE-CP-001 - Beranda
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Route /. Urutan section sesuai PAGE-CP-001; navbar dan footer dari layout.
    Status  : section statis terimplementasi; preview data menunggu ADM/CAT.
--}}
@extends('layouts.public')

@section('title', 'Beranda')
@section('meta_description', 'Your Personal Merchandise Solution. Jelajahi layanan merchandise custom dan printing YOLOO Merch.')

@section('content')
    @include('public.home.sections.hero')
    @include('public.home.sections.intro')
    @include('public.home.sections.featured-products')
    @include('public.home.sections.services')
    @include('public.home.sections.advantages')
    @include('public.home.sections.collaboration-preview')
    @include('public.home.sections.gallery-preview')
    @include('public.home.sections.testimonial-preview')
    @include('public.home.sections.cta')
@endsection
