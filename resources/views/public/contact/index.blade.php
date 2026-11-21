{{--
    PAGE-CP-005 - Kontak
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Route /kontak.
    Status  : kanal awal dari PRD ditampilkan; perlu verifikasi pemilik dan integrasi SiteSetting ADM.
--}}
@extends('layouts.public')

@section('title', 'Kontak')
@section('meta_description', 'Informasi kontak resmi YOLOO Merch untuk pertanyaan tentang produk dan layanan.')

@section('content')
    @include('public.contact.sections.channels')
    @include('public.contact.sections.location-hours')
@endsection
