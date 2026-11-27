{{--
    PAGE-ADM-003 - Daftar Kolaborasi
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Daftar Kolaborasi')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Daftar Kolaborasi',
        'requirement' => 'ADM-FR-007 (Testimonial & Portfolio Management)',
        'note' => 'Dokumentasi proyek dan kerja sama yang tampil sebagai portofolio.',
    ])
@endsection
