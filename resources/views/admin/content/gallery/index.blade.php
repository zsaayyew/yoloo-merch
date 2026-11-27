{{--
    PAGE-ADM-003 - Daftar Foto Galeri
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Daftar Foto Galeri')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Daftar Foto Galeri',
        'requirement' => 'ADM-FR-007 (Testimonial & Portfolio Management)',
        'note' => 'Foto galeri beserta kategori dan status publikasinya.',
    ])
@endsection
