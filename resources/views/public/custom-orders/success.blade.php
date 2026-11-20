{{--
    PAGE-ORD-006 - Request Berhasil Dikirim
    Domain  : PRD-03 ORD - Customer Ordering (Raisyah)
    Catatan : Route /custom-order/berhasil/{kode}. Hanya untuk sesi pengirim. Tanpa instruksi pembayaran.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Request Berhasil Dikirim')

@section('content')
    @include('public.custom-orders.sections.confirmation-details')
@endsection
