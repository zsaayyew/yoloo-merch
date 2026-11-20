{{--
    PAGE-CP-002 - Tentang Kami
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Route /tentang-kami.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Tentang Kami')

@section('content')
    @include('public.about.sections.profile')
    @include('public.about.sections.vision-mission')
    @include('public.about.sections.values-advantages')
    @include('public.about.sections.ordering-guide')
@endsection
