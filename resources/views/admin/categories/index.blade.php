{{--
    PAGE-ADM-004 - Kategori Produk
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Kategori Produk')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Kategori Produk',
        'requirement' => 'ADM-FR-004 (Product & Category Management)',
        'note' => 'Daftar, tambah, ubah, dan arsip kategori produk.',
    ])
@endsection
