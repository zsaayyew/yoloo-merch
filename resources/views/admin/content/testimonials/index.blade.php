{{--
    PAGE-ADM-003 - Daftar Testimoni
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Daftar Testimoni')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Daftar Testimoni',
        'requirement' => 'ADM-FR-007 (Testimonial & Portfolio Management)',
        'note' => 'Kurasi ulasan pelanggan yang tampil di website.',
    ])
@endsection
