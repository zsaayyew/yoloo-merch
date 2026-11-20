{{--
    PAGE-ADM-004 - Ubah Konfigurasi Harga Produk
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Ubah Konfigurasi Harga Produk')

@section('content')
    @include('admin.pricing.sections.price-model')
    @include('admin.pricing.sections.parameter-groups')
    @include('admin.pricing.sections.tariff-matrix')
    @include('admin.pricing.sections.quantity-tiers')
    @include('admin.pricing.sections.extra-fees')
@endsection
