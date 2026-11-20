{{--
    PAGE-CP-003 - Ulasan & Kolaborasi
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Route /ulasan-kolaborasi.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Ulasan & Kolaborasi')

@section('content')
    @include('public.reviews.sections.testimonials')
    @include('public.reviews.sections.collaborations')
@endsection
