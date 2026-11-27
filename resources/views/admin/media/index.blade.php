{{--
    PAGE-ADM-005 - Media
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/media.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Media')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Media',
        'requirement' => 'ADM-FR-008 (Media & Publication Management)',
        'note' => 'Unggah dan kelola berkas media yang dipakai konten website.',
    ])
@endsection
