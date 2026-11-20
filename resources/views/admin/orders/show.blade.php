{{--
    PAGE-FIN-002 - Detail Order / Request
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : payments + refunds = PAGE-FIN-005. Modal aksi ada di admin/orders/modals.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Detail Order / Request')

@section('content')
    @include('admin.orders.sections.customer-info')
    @include('admin.orders.sections.items')
    @include('admin.orders.sections.attachments')
    @include('admin.orders.sections.request-status')
    @include('admin.orders.sections.quotation')
    @include('admin.orders.sections.order-status')
    @include('admin.orders.sections.status-history')
    @include('admin.orders.sections.payments')
    @include('admin.orders.sections.refunds')
    @include('admin.orders.sections.invoices')
    @include('admin.orders.sections.internal-notes')
@endsection
