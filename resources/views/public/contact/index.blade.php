{{--
    PAGE-CP-005 - Kontak
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Route /kontak.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Kontak')

@section('content')
    @include('public.contact.sections.channels')
    @include('public.contact.sections.location-hours')
@endsection
