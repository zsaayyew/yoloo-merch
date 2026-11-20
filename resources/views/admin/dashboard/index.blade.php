{{--
    PAGE-ADM-002 - Dashboard
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/dashboard. sales-summary diisi PRD-05 (subfitur Could).
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @include('admin.dashboard.sections.content-summary')
    @include('admin.dashboard.sections.sales-summary')
@endsection
