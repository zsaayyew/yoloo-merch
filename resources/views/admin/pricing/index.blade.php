{{--
    PAGE-ADM-004 - Konfigurasi Harga
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/pricing (ADM-FR-006). Website memakai baris aktif dengan
              effective_date terbaru, lalu version tertinggi.
--}}
@extends('layouts.admin')

@section('title', 'Konfigurasi Harga')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Konfigurasi Harga"
            description="Setiap perubahan tarif disimpan sebagai versi baru agar transaksi lama tetap konsisten." />

        @forelse ($products as $product)
            <section class="space-y-3 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-black tracking-tight text-[#181818]">{{ $product->name }}</h2>
                        <p class="text-xs text-[#181818]/50">
                            {{ $product->category?->name ?? 'Tanpa kategori' }} &middot;
                            {{ $priceModels[$product->price_model] ?? $product->price_model }}
                        </p>
                    </div>

                    <a href="{{ route('admin.pricing.create', $product) }}"
                       class="inline-flex min-h-10 items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white hover:bg-[#333]">
                        Buat Versi Harga
                    </a>
                </div>

                @if ($product->pricingConfigurations->isEmpty())
                    <p class="rounded-2xl border border-dashed border-[#181818]/20 px-4 py-6 text-center text-sm text-[#181818]/50">
                        Belum ada konfigurasi harga untuk produk ini.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#181818]/10 text-sm">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Versi</th>
                                    <th class="px-3 py-2 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Berlaku</th>
                                    <th class="px-3 py-2 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Harga Dasar</th>
                                    <th class="px-3 py-2 text-right text-xs font-bold uppercase tracking-wider text-[#181818]/50">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#181818]/5">
                                @foreach ($product->pricingConfigurations as $pricing)
                                    <tr>
                                        <td class="px-3 py-2 font-bold text-[#181818]">v{{ $pricing->version }}</td>
                                        <td class="px-3 py-2">
                                            <span @class([
                                                'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                                'bg-[#DDF3F0] text-[#124f53]' => $pricing->status === 'active',
                                                'bg-[#181818]/10 text-[#181818]/60' => $pricing->status !== 'active',
                                            ])>
                                                {{ $pricing->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-[#181818]/70">{{ $pricing->effective_date?->translatedFormat('j M Y') }}</td>
                                        <td class="px-3 py-2 text-[#181818]/70">Rp {{ number_format($pricing->base_price, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2">
                                            <div class="flex flex-wrap items-center justify-end gap-3">
                                                <a href="{{ route('admin.pricing.edit', $pricing) }}"
                                                   class="text-xs font-bold text-[#181818] hover:underline">Ubah</a>

                                                <form method="POST" action="{{ route('admin.pricing.toggle', $pricing) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-xs font-bold text-[#124f53] hover:underline">
                                                        {{ $pricing->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        @empty
            <p class="rounded-3xl border border-dashed border-[#181818]/20 bg-white px-4 py-10 text-center text-sm text-[#181818]/50">
                Belum ada produk. Tambahkan produk lebih dulu.
            </p>
        @endforelse
    </div>
@endsection
