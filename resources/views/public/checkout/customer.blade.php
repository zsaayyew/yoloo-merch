{{--
    PAGE-ORD-002 - Checkout - Data Pelanggan
    Domain  : PRD-03 ORD - Customer Ordering (Raisyah)
    Catatan : Route /checkout. Pickup saja.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Checkout - Data Pelanggan')

@section('content')
    @include('public.checkout.sections.customer-form')
    @include('public.checkout.sections.item-attachments')
    @include('public.checkout.sections.cart-recap')
@endsection
