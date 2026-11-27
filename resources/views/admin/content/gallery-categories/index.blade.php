{{--
    PAGE-ADM-003 - Kategori Galeri
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Kategori Galeri')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Kategori Galeri',
        'requirement' => 'ADM-FR-007 (Testimonial & Portfolio Management)',
        'note' => 'Pengelompokan foto galeri di website.',
    ])
@endsection
