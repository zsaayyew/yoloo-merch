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
                            {{ $group->values->count() }} nilai &middot; {{ $group->is_required ? 'wajib dipilih' : 'opsional' }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.products.options.destroy', [$product, $group]) }}"
                          onsubmit="return confirm('Hapus grup opsi beserta nilainya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm font-bold text-[#b3261e] hover:underline">Hapus Grup</button>
                    </form>
                </div>

                @if ($group->values->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#181818]/10 text-sm">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Nilai</th>
                                    <th class="px-3 py-2 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Status</th>
                                    <th class="px-3 py-2 text-right text-xs font-bold uppercase tracking-wider text-[#181818]/50">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#181818]/5">
                                @foreach ($group->values as $value)
                                    <tr>
                                        <td class="px-3 py-2 font-semibold text-[#181818]">{{ $value->label }}</td>
                                        <td class="px-3 py-2">
                                            <span @class([
                                                'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                                'bg-[#DDF3F0] text-[#124f53]' => $value->is_active,
                                                'bg-[#181818]/10 text-[#181818]/60' => ! $value->is_active,
                                            ])>
                                                {{ $value->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="flex flex-wrap items-center justify-end gap-3">
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
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
