{{--
    PAGE-CP-005 - Kontak
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Route /kontak.
    Status  : kanal awal dari PRD ditampilkan; perlu verifikasi pemilik dan integrasi SiteSetting ADM.
--}}
@extends('layouts.public')

@section('title', 'Kontak')
@section('meta_description', 'Hubungi YOLOO Merch melalui WhatsApp, Instagram, atau email.')

@section('content')
    @include('public.contact.sections.channels')
    @include('public.contact.sections.location-hours')
@endsection
