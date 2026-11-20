{{--
    PAGE-FIN-009 - Laporan
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
    @include('admin.reports.sections.filters')
    @include('admin.reports.sections.summary')
    @include('admin.reports.sections.sales')
    @include('admin.reports.sections.payments')
    @include('admin.reports.sections.refunds')
    @include('admin.reports.sections.material-purchases')
    @include('admin.reports.sections.operating-expenses')
@endsection
