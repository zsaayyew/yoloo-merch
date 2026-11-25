{{--
    PAGE-ADM-003 - Konten Tentang Kami
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Konten Tentang Kami')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Konten Tentang Kami',
        'requirement' => 'ADM-FR-003 (Content Management)',
        'note' => 'Cerita usaha, visi, dan informasi profil yang tampil di halaman tentang.',
    ])
@endsection
