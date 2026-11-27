{{--
    PAGE-ADM-006 - Manajemen Akun Admin
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/users. Owner saja (Admin -> 403).
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Manajemen Akun Admin')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Manajemen Akun Admin',
        'requirement' => 'ADM-FR-009 (User & Role Management)',
        'note' => 'Hanya Owner yang dapat membuka halaman ini; Admin menerima 403.',
    ])
@endsection
