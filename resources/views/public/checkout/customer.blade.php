{{--
    PAGE-ORD-002 - Checkout: Data Pelanggan & Informasi Pickup
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : ORD-FR-005, ORD-FR-006. Tanpa akun, tanpa alamat/ongkir. Data disimpan di sesi.
    Status  : diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Checkout')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-10 sm:py-14">
        <p class="text-sm font-medium text-gray-500">Langkah 1 dari 2</p>
        <h1 class="mt-1 text-3xl font-bold text-gray-900 sm:text-4xl">Data kontak</h1>
        <p class="mt-2 text-gray-600">Tidak perlu membuat akun. Admin akan menghubungimu lewat WhatsApp.</p>

        @if ($errors->has('customer'))
            <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                {{ $errors->first('customer') }}
            </div>
        @endif

        <div class="mt-8 grid gap-8 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                @include('public.checkout.sections.customer-form')
                <x-ordering.pickup-info />
            </div>
            <div>
                @include('public.checkout.sections.cart-recap')
            </div>
        </div>
    </section>
@endsection
