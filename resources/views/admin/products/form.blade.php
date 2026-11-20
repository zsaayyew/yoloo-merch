{{--
    PAGE-ADM-004 - Form Produk
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : purchase-settings = penanda boleh direct purchase dan wajib file desain (dibaca ORD).
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Form Produk')

@section('content')
    @include('admin.products.sections.basic-info')
    @include('admin.products.sections.images')
    @include('admin.products.sections.options')
    @include('admin.products.sections.purchase-settings')
@endsection
