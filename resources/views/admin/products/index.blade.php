{{--
    PAGE-ADM-004 - Daftar Produk
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/products.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Daftar Produk',
        'requirement' => 'ADM-FR-004 s.d. ADM-FR-005 (Product & Variant Management)',
        'note' => 'Daftar, tambah, ubah, dan arsip produk beserta opsinya.',
    ])
@endsection
