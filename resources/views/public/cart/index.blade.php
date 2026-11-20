{{--
    PAGE-ORD-001 - Keranjang
    Domain  : PRD-03 ORD - Customer Ordering (Raisyah)
    Catatan : Route /keranjang. Tanpa ongkir.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Keranjang')

@section('content')
    @include('public.cart.sections.item-list')
    @include('public.cart.sections.totals')
@endsection
