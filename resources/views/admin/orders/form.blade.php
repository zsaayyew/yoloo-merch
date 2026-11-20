{{--
    PAGE-FIN-003 - Form Transaksi Manual
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Route /admin/orders/create dan /admin/orders/{id}/edit. Pickup saja.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Form Transaksi Manual')

@section('content')
    @include('admin.orders.sections.customer-picker')
    @include('admin.orders.sections.items-editor')
    @include('admin.orders.sections.totals')
@endsection
