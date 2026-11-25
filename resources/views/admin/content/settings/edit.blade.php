{{--
    PAGE-ADM-003 - Profil Usaha, Kontak, dan Pickup
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : WhatsApp, Instagram, email, alamat/jam, lokasi pickup. Tidak menyimpan QRIS/rekening.
    Status  : placeholder, belum diimplementasikan.
--}}
@extends('layouts.admin')

@section('title', 'Profil Usaha, Kontak, dan Pickup')

@section('content')
    @include('admin.partials.module-placeholder', [
        'title' => 'Profil Usaha, Kontak, dan Pickup',
        'requirement' => 'ADM-FR-003 (Content Management)',
        'note' => 'WhatsApp, Instagram, email, alamat/jam, dan lokasi pickup.',
    ])
@endsection
