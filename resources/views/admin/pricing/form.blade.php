{{--
    PAGE-ADM-004 - Form Konfigurasi Harga
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Struktur config mengikuti PriceCalculator: minimum_quantity, maximum_quantity,
              quantity_tiers[min,max,discount_per_unit], option_surcharges, matrix, extra_fee, minimum_total.
--}}
@extends('layouts.admin')

@section('title', 'Konfigurasi Harga '.$product->name)

@section('content')
    @php
        $editing = $pricing !== null;

        $tiers = collect($config['quantity_tiers'] ?? [])
            ->map(fn ($tier) => [
                'min' => $tier['min'] ?? '',
                'max' => $tier['max'] ?? '',
                'discount_per_unit' => $tier['discount_per_unit'] ?? '',
            ])
            ->values()
            ->take(4)
            ->all();

        while (count($tiers) < 4) {
            $tiers[] = ['min' => '', 'max' => '', 'discount_per_unit' => ''];
        }

        $matrix = collect($config['matrix'] ?? [])
            ->map(fn ($row) => [
                'ids' => (array) ($row['option_value_ids'] ?? []),
                'additional_price' => $row['additional_price'] ?? '',
            ])
            ->values()
            ->take(3)
            ->all();

        while (count($matrix) < 3) {
            $matrix[] = ['ids' => [], 'additional_price' => ''];
        }

        $optionValues = $product->optionGroups->flatMap->values;
        $surcharges = (array) ($config['option_surcharges'] ?? []);
    @endphp

    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header
            :title="'Harga '.$product->name"
            :description="'Versi '.$version.' · model harga mengikuti produk ('.$priceModels[$product->price_model].').'">
            <x-slot:actions>
                <a href="{{ route('admin.pricing.index') }}"
                   class="inline-flex min-h-10 items-center justify-center rounded-full border-2 border-[#181818] px-4 py-2 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                    Daftar Harga
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="POST"
              action="{{ $editing ? route('admin.pricing.update', $pricing) : route('admin.pricing.store', $product) }}"
              class="space-y-4">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif

            <section class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <h2 class="text-lg font-black tracking-tight text-[#181818]">Dasar Tarif</h2>

                <div class="grid gap-4 lg:grid-cols-2">
                    @include('admin.partials.field-select', [
                        'name' => 'price_model',
                        'label' => 'Model Harga',
                        'value' => $pricing->price_model ?? $product->price_model,
                        'options' => $priceModels,
                        'required' => true,
                    ])

                    @include('admin.partials.field-select', [
                        'name' => 'status',
                        'label' => 'Status',
                        'value' => $pricing->status ?? 'active',
                        'options' => ['active' => 'Aktif', 'inactive' => 'Nonaktif'],
                        'required' => true,
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'effective_date',
                        'label' => 'Tanggal Berlaku',
                        'value' => $effectiveDate,
                        'type' => 'date',
                        'required' => true,
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'base_price',
                        'label' => 'Harga Dasar (Rp)',
                        'value' => $basePrice,
                        'type' => 'number',
                        'required' => true,
                        'hint' => 'Harga per unit sebelum surcharge dan diskon tier.',
                    ])
                </div>
            </section>

            <section class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <h2 class="text-lg font-black tracking-tight text-[#181818]">Rentang &amp; Biaya Tambahan</h2>

                <div class="grid gap-4 lg:grid-cols-2">
                    @include('admin.partials.field-input', [
                        'name' => 'minimum_quantity',
                        'label' => 'Kuantitas Minimum',
                        'value' => $config['minimum_quantity'] ?? '',
                        'type' => 'number',
                        'hint' => 'Kosongkan bila mengikuti batas bawah 1.',
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'maximum_quantity',
                        'label' => 'Kuantitas Maksimum',
                        'value' => $config['maximum_quantity'] ?? '',
                        'type' => 'number',
                        'hint' => 'Kosongkan bila tanpa batas atas.',
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'extra_fee',
                        'label' => 'Biaya Tambahan (Rp)',
                        'value' => $config['extra_fee'] ?? '',
                        'type' => 'number',
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'minimum_total',
                        'label' => 'Total Minimum (Rp)',
                        'value' => $config['minimum_total'] ?? '',
                        'type' => 'number',
                    ])
                </div>
            </section>

            <section class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <div>
                    <h2 class="text-lg font-black tracking-tight text-[#181818]">Tier Kuantitas</h2>
                    <p class="text-sm text-[#181818]/60">Diskon per unit untuk rentang kuantitas. Baris tanpa kuantitas minimum diabaikan.</p>
                </div>

                @foreach ($tiers as $index => $tier)
                    <div class="grid gap-3 sm:grid-cols-3">
                        @include('admin.partials.field-input', [
                            'name' => "tiers.{$index}.min",
                            'label' => 'Dari Kuantitas',
                            'value' => $tier['min'],
                            'type' => 'number',
                        ])

                        @include('admin.partials.field-input', [
                            'name' => "tiers.{$index}.max",
                            'label' => 'Sampai Kuantitas',
                            'value' => $tier['max'],
                            'type' => 'number',
                        ])

                        @include('admin.partials.field-input', [
                            'name' => "tiers.{$index}.discount_per_unit",
                            'label' => 'Diskon per Unit (Rp)',
                            'value' => $tier['discount_per_unit'],
                            'type' => 'number',
                        ])
                    </div>
                @endforeach
            </section>

            @if ($optionValues->isNotEmpty())
                <section class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                    <div>
                        <h2 class="text-lg font-black tracking-tight text-[#181818]">Surcharge Opsi</h2>
                        <p class="text-sm text-[#181818]/60">Tambahan harga per nilai opsi, hanya dipakai model kalkulator.</p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($optionValues as $value)
                            @include('admin.partials.field-input', [
                                'name' => "surcharges.{$value->id}",
                                'label' => $value->group?->name.' - '.$value->label,
                                'value' => $surcharges[$value->id] ?? '',
                                'type' => 'number',
                            ])
                        @endforeach
                    </div>
                </section>

                <section class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                    <div>
                        <h2 class="text-lg font-black tracking-tight text-[#181818]">Matrix Kombinasi</h2>
                        <p class="text-sm text-[#181818]/60">
                            Bila diisi, harga kombinasi opsi hanya diambil dari matrix ini. Pilih semua nilai opsi pada kombinasi tersebut.
                        </p>
                    </div>

                    @foreach ($matrix as $index => $row)
                        <div class="grid gap-3 sm:grid-cols-[2fr_1fr]">
                            <div>
                                <label for="matrix-{{ $index }}" class="block text-sm font-bold text-[#181818]">Kombinasi {{ $index + 1 }}</label>
                                <select id="matrix-{{ $index }}" name="matrix[{{ $index }}][ids][]" multiple size="4"
                                        class="mt-1 w-full rounded-xl border border-[#181818]/20 bg-white px-3 py-2 text-sm">
                                    @foreach ($optionValues as $value)
                                        <option value="{{ $value->id }}" @selected(in_array($value->id, $row['ids'], true))>
                                            {{ $value->group?->name }} - {{ $value->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @include('admin.partials.field-input', [
                                'name' => "matrix.{$index}.additional_price",
                                'label' => 'Harga Tambahan Kombinasi (Rp)',
                                'value' => $row['additional_price'],
                                'type' => 'number',
                            ])
                        </div>
                    @endforeach
                </section>
            @endif

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('admin.pricing.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-full border-2 border-[#181818] px-5 py-2.5 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#333]">
                    {{ $editing ? 'Simpan Perubahan' : 'Simpan Versi Harga' }}
                </button>
            </div>
        </form>
    </div>
@endsection
