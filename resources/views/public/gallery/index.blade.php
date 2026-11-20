{{--
    PAGE-CP-004 - Galeri
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Route /galeri. Lightbox memakai <x-ui.lightbox />.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Galeri')

@section('content')
    @include('public.gallery.sections.filter')
    @include('public.gallery.sections.grid')
@endsection
