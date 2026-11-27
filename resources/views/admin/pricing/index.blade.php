{{--
    PAGE-ADM-004 - Konfigurasi Harga
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/pricing.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Konfigurasi Harga')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Konfigurasi Harga',
        'requirement' => 'ADM-FR-006 (Pricing Configuration)',
        'note' => 'Pengaturan harga dasar, tarif opsi, dan aturan kalkulator.',
    ])
@endsection
