{{--
    PAGE-ORD-005 - Formulir Custom Order
    Domain  : PRD-03 ORD - Customer Ordering (Raisyah)
    Catatan : Route /custom-order (opsional ?produk={slug}).
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Formulir Custom Order')

@section('content')
    @include('public.custom-orders.sections.product-reference')
    @include('public.custom-orders.sections.requirements')
    @include('public.custom-orders.sections.reference-files')
    @include('public.custom-orders.sections.contact-form')
    @include('public.custom-orders.sections.agreement')
@endsection
