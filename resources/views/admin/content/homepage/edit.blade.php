{{--
    PAGE-ADM-003 - Konten Beranda
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Konten Beranda')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Konten Beranda',
        'requirement' => 'ADM-FR-003 (Content Management)',
        'note' => 'Teks, gambar, dan status publikasi beranda.',
    ])
@endsection
