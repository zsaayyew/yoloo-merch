{{--
    PAGE-ORD-003 - Checkout: Ringkasan & Persetujuan
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : ORD-FR-007, ORD-FR-008, ORD-FR-013. Tidak menampilkan rekening, QRIS,
              atau instruksi pembayaran. Harga dihitung ulang server saat kirim.
    Status  : diimplementasikan.
--}}
@extends('layouts.public')

@section('title', 'Ringkasan Pesanan')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-10 sm:py-14">
        <p class="text-sm font-medium text-gray-500">Langkah 2 dari 2</p>
        <h1 class="mt-1 text-3xl font-bold text-gray-900 sm:text-4xl">Ringkasan pesanan</h1>
        <p class="mt-2 text-gray-600">Periksa kembali pesananmu sebelum dikirim.</p>

        @if ($errors->any())
            <div @class([
                'mt-6 rounded-lg border p-4 text-sm',
                'border-amber-300 bg-amber-50 text-amber-900' => $errors->has('price_changed'),
                'border-red-200 bg-red-50 text-red-700' => ! $errors->has('price_changed'),
            ]) role="alert">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif

        <div class="mt-8 grid gap-8 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                @include('public.checkout.sections.review-items')
                @include('public.checkout.sections.review-contact')
                <x-ordering.pickup-info />
            </div>
            <div>
                @include('public.checkout.sections.agreement')
            </div>
        </div>
    </section>
@endsection
