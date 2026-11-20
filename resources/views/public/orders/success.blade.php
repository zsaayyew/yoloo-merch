{{--
    PAGE-ORD-004 - Pesanan Berhasil Dikirim
    Domain  : PRD-03 ORD - Customer Ordering (Raisyah)
    Catatan : Route /pesanan/berhasil/{kode}. Hanya untuk sesi pengirim. Kode = bukti pengajuan, bukan cek status.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Pesanan Berhasil Dikirim')

@section('content')
    @include('public.orders.sections.confirmation-details')
    @include('public.orders.sections.next-steps')
@endsection
