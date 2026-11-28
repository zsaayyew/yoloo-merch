{{--
    PAGE-ADM-004 - Opsi Produk
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/products/{product}/options (ADM-FR-005).
--}}
@extends('layouts.admin')

@section('title', 'Opsi '.$product->name)

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Opsi: {{ $product->name }}"
            description="Grup opsi wajib harus dipilih pelanggan sebelum produk masuk keranjang.">
            <x-slot:actions>
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="inline-flex min-h-10 items-center justify-center rounded-full border-2 border-[#181818] px-4 py-2 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                    Kembali ke Produk
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="POST" action="{{ route('admin.products.options.store', $product) }}"
              class="grid gap-3 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:grid-cols-2 lg:grid-cols-4 sm:p-6">
            @csrf

            <div class="lg:col-span-2">
                @include('admin.partials.field-input', [
                    'name' => 'name',
                    'label' => 'Nama Grup Opsi',
                    'value' => '',
                    'required' => true,
                    'hint' => 'Contoh: Bahan, Ukuran, Finishing',
                ])
            </div>

            @include('admin.partials.field-input', [
                'name' => 'sort_order',
                'label' => 'Urutan',
                'value' => 0,
                'type' => 'number',
            ])

            <div class="flex items-end">
                <button type="submit"
                        class="inline-flex min-h-10 w-full items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white hover:bg-[#333]">
                    Tambah Grup
                </button>
            </div>

            <div class="sm:col-span-2 lg:col-span-4">
                @include('admin.partials.field-checkbox', [
                    'name' => 'is_required',
                    'label' => 'Pelanggan wajib memilih salah satu nilai',
                    'checked' => false,
                ])
            </div>
        </form>

        @forelse ($product->optionGroups as $group)
            <section class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-black tracking-tight text-[#181818]">{{ $group->name }}</h2>
                        <p class="text-xs text-[#181818]/50">
                            Urutan {{ $group->sort_order }} &middot; {{ $group->values->count() }} nilai &middot;
                            {{ $group->is_required ? 'wajib dipilih' : 'opsional' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <details class="text-sm">
                            <summary class="cursor-pointer font-bold text-[#124f53] hover:underline">Ubah Grup</summary>

                            <form method="POST" action="{{ route('admin.products.options.update', [$product, $group]) }}"
                                  class="mt-3 grid gap-3 rounded-2xl border border-[#181818]/10 p-4 sm:grid-cols-2 lg:grid-cols-4">
                                @csrf
                                @method('PUT')

                                <div class="lg:col-span-2">
                                    @include('admin.partials.field-input', [
                                        'name' => 'name',
                                        'label' => 'Nama Grup Opsi',
                                        'value' => $group->name,
                                        'required' => true,
                                    ])
                                </div>

                                @include('admin.partials.field-input', [
                                    'name' => 'sort_order',
                                    'label' => 'Urutan',
                                    'value' => $group->sort_order,
                                    'type' => 'number',
                                ])

                                <div class="flex items-end">
                                    <button type="submit"
                                            class="inline-flex min-h-10 w-full items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white hover:bg-[#333]">
                                        Simpan Grup
                                    </button>
                                </div>

                                <div class="sm:col-span-2 lg:col-span-4">
                                    @include('admin.partials.field-checkbox', [
                                        'name' => 'is_required',
                                        'label' => 'Pelanggan wajib memilih salah satu nilai',
                                        'checked' => $group->is_required,
                                    ])
                                </div>
                            </form>
                        </details>

                        <form method="POST" action="{{ route('admin.products.options.destroy', [$product, $group]) }}"
                              onsubmit="return confirm('Hapus grup opsi beserta nilainya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-bold text-[#b3261e] hover:underline">Hapus Grup</button>
                        </form>
                    </div>
                </div>

                @if ($group->values->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($group->values as $value)
                            <div class="rounded-2xl border border-[#181818]/10 p-3 sm:p-4">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-[#181818]">{{ $value->label }}</p>
                                        <p class="text-xs text-[#181818]/50">
                                            Urutan {{ $value->sort_order }}
                                            @if (! empty($value->forbidden_combinations))
                                                &middot; tidak boleh dipilih dengan:
                                                {{ $product->optionGroups->flatMap->values->whereIn('id', $value->forbidden_combinations)->pluck('label')->implode(', ') }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-3">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                            'bg-[#DDF3F0] text-[#124f53]' => $value->is_active,
                                            'bg-[#181818]/10 text-[#181818]/60' => ! $value->is_active,
                                        ])>
                                            {{ $value->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>

                                        <form method="POST"
                                              action="{{ route('admin.products.options.values.toggle', [$product, $group, $value]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs font-bold text-[#124f53] hover:underline">
                                                {{ $value->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('admin.products.options.values.destroy', [$product, $group, $value]) }}"
                                              onsubmit="return confirm('Hapus nilai opsi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-[#b3261e] hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </div>

                                <details class="mt-2">
                                    <summary class="cursor-pointer text-xs font-bold text-[#124f53] hover:underline">Ubah Nilai</summary>

                                    <form method="POST"
                                          action="{{ route('admin.products.options.values.update', [$product, $group, $value]) }}"
                                          class="mt-3 grid gap-3 border-t border-[#181818]/10 pt-3 sm:grid-cols-2 lg:grid-cols-4">
                                        @csrf
                                        @method('PUT')

                                        <div class="lg:col-span-2">
                                            @include('admin.partials.field-input', [
                                                'name' => 'label',
                                                'label' => 'Nilai',
                                                'value' => $value->label,
                                                'required' => true,
                                            ])
                                        </div>

                                        @include('admin.partials.field-input', [
                                            'name' => 'sort_order',
                                            'label' => 'Urutan',
                                            'value' => $value->sort_order,
                                            'type' => 'number',
                                        ])

                                        <div class="flex items-end">
                                            <button type="submit"
                                                    class="inline-flex min-h-10 w-full items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white hover:bg-[#333]">
                                                Simpan Nilai
                                            </button>
                                        </div>

                                        <div class="sm:col-span-2 lg:col-span-4">
                                            @include('admin.partials.field-checkbox', [
                                                'name' => 'is_active',
                                                'label' => 'Nilai aktif dan bisa dipilih pelanggan',
                                                'checked' => $value->is_active,
                                            ])
                                        </div>

                                        @php
                                            $selected = array_map('intval', (array) old(
                                                'forbidden_combinations',
                                                $value->forbidden_combinations ?? [],
                                            ));
                                        @endphp

                                        <div class="sm:col-span-2 lg:col-span-4">
                                            <p class="text-sm font-bold text-[#181818]">Kombinasi terlarang</p>
                                            <p class="text-xs text-[#181818]/50">
                                                Nilai yang tidak boleh dipilih bersamaan dengan "{{ $value->label }}".
                                            </p>

                                            @if ($product->optionGroups->flatMap->values->reject(fn ($item) => $item->id === $value->id)->isEmpty())
                                                <p class="mt-2 text-xs text-[#181818]/50">Belum ada nilai lain pada produk ini.</p>
                                            @else
                                                <div class="mt-2 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                                    @foreach ($product->optionGroups as $otherGroup)
                                                        @continue($otherGroup->values->reject(fn ($item) => $item->id === $value->id)->isEmpty())

                                                        <fieldset class="rounded-xl border border-[#181818]/10 p-3">
                                                            <legend class="px-1 text-xs font-bold uppercase tracking-wider text-[#181818]/50">
                                                                {{ $otherGroup->name }}
                                                            </legend>

                                                            <div class="space-y-1.5">
                                                                @foreach ($otherGroup->values as $other)
                                                                    @continue($other->id === $value->id)

                                                                    <label class="flex items-start gap-2 text-sm text-[#181818]">
                                                                        <input type="checkbox"
                                                                               name="forbidden_combinations[]"
                                                                               value="{{ $other->id }}"
                                                                               @checked(in_array($other->id, $selected, true))
                                                                               class="mt-0.5 h-4 w-4 rounded border-[#181818]/30 text-[#14747b] focus:ring-[#58C0C8]">
                                                                        <span>{{ $other->label }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </fieldset>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @error('forbidden_combinations')
                                                <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
                                            @enderror
                                            @error('forbidden_combinations.*')
                                                <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </form>
                                </details>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.products.options.values.store', [$product, $group]) }}"
                      class="grid gap-3 border-t border-[#181818]/10 pt-4 sm:grid-cols-2 lg:grid-cols-4">
                    @csrf

                    <div class="lg:col-span-2">
                        @include('admin.partials.field-input', [
                            'name' => 'label',
                            'label' => 'Nilai Baru',
                            'value' => '',
                            'required' => true,
                            'hint' => 'Contoh: Katun Combed 30s',
                        ])
                    </div>

                    @include('admin.partials.field-input', [
                        'name' => 'sort_order',
                        'label' => 'Urutan',
                        'value' => 0,
                        'type' => 'number',
                    ])

                    <div class="flex items-end">
                        <button type="submit"
                                class="inline-flex min-h-10 w-full items-center justify-center rounded-full border-2 border-[#181818] px-4 py-2 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                            Tambah Nilai
                        </button>
                    </div>
                </form>
            </section>
        @empty
            <p class="rounded-3xl border border-dashed border-[#181818]/20 bg-white px-4 py-10 text-center text-sm text-[#181818]/50">
                Produk ini belum punya grup opsi.
            </p>
        @endforelse
    </div>
@endsection
