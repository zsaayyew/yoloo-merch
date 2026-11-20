{{--
    PAGE-ORD-003 - Ringkasan dan Persetujuan Pesanan
    Domain  : PRD-03 ORD - Customer Ordering (Raisyah)
    Catatan : Route /checkout/ringkasan. Tanpa QRIS/rekening/instruksi pembayaran.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Ringkasan dan Persetujuan Pesanan')

@section('content')
    @include('public.checkout.sections.review-items')
    @include('public.checkout.sections.review-contact')
    @include('public.checkout.sections.agreement')
@endsection
