{{--
    PAGE-ADM-004 - Form Produk
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/products/create dan /admin/products/{product}/edit (ADM-FR-004).
--}}
@extends('layouts.admin')

@section('title', $product->exists ? 'Ubah Produk' : 'Tambah Produk')

@section('content')
    @php
        $editing = $product->exists;

        $specRows = collect($specifications)
            ->map(fn ($value, $label) => ['label' => (string) $label, 'value' => (string) $value])
            ->values()
            ->take(5)
            ->all();

        while (count($specRows) < 5) {
            $specRows[] = ['label' => '', 'value' => ''];
        }
    @endphp

    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header
            :title="$editing ? 'Ubah Produk' : 'Tambah Produk'"
            description="Lengkapi informasi produk, gambar, opsi, lalu atur konfigurasi harganya.">
            @if ($editing)
                <x-slot:actions>
                    <a href="{{ route('admin.products.options.index', $product) }}"
                       class="inline-flex min-h-10 items-center justify-center rounded-full border-2 border-[#181818] px-4 py-2 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                        Kelola Opsi
                    </a>
                    <a href="{{ route('admin.pricing.create', $product) }}"
                       class="inline-flex min-h-10 items-center justify-center rounded-full border-2 border-[#181818] px-4 py-2 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                        Atur Harga
                    </a>
                </x-slot:actions>
            @endif
        </x-admin.page-header>

        <form method="POST"
              action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}"
              enctype="multipart/form-data" class="space-y-4">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif

            <section class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <h2 class="text-lg font-black tracking-tight text-[#181818]">Informasi Dasar</h2>

                <div class="grid gap-4 lg:grid-cols-2">
                    @include('admin.partials.field-input', [
                        'name' => 'name',
                        'label' => 'Nama Produk',
                        'value' => $product->name,
                        'required' => true,
                    ])

                    @include('admin.partials.field-select', [
                        'name' => 'category_id',
                        'label' => 'Kategori',
                        'value' => $product->category_id,
                        'options' => $categories->pluck('name', 'id')->all(),
                        'placeholder' => 'Pilih kategori',
                        'required' => true,
                    ])
                </div>

                @include('admin.partials.field-textarea', [
                    'name' => 'description',
                    'label' => 'Deskripsi',
                    'value' => $product->description,
                    'rows' => 4,
                ])

                <div class="grid gap-4 lg:grid-cols-2">
                    @include('admin.partials.field-select', [
                        'name' => 'price_model',
                        'label' => 'Model Harga',
                        'value' => $product->price_model,
                        'options' => $priceModels,
                        'required' => true,
                    ])

                    @include('admin.partials.field-select', [
                        'name' => 'status',
                        'label' => 'Status Produk',
                        'value' => $product->status,
                        'options' => $statuses,
                        'required' => true,
                    ])
                </div>

                @include('admin.partials.field-checkbox', [
                    'name' => 'allows_direct_purchase',
                    'label' => 'Boleh dipesan langsung lewat katalog',
                    'checked' => $product->allows_direct_purchase,
                    'hint' => 'Matikan bila produk hanya dilayani melalui permintaan penawaran.',
                ])
            </section>

            <section class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <div>
                    <h2 class="text-lg font-black tracking-tight text-[#181818]">Spesifikasi</h2>
                    <p class="text-sm text-[#181818]/60">Isi nama dan nilai spesifikasi. Baris kosong diabaikan.</p>
                </div>

                @foreach ($specRows as $index => $row)
                    <div class="grid gap-3 sm:grid-cols-2">
                        @include('admin.partials.field-input', [
                            'name' => "specifications.{$index}.label",
                            'label' => 'Nama Spesifikasi',
                            'value' => $row['label'],
                        ])

                        @include('admin.partials.field-input', [
                            'name' => "specifications.{$index}.value",
                            'label' => 'Nilai',
                            'value' => $row['value'],
                        ])
                    </div>
                @endforeach
            </section>

            <section class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <div>
                    <h2 class="text-lg font-black tracking-tight text-[#181818]">Gambar Produk</h2>
                    <p class="text-sm text-[#181818]/60">Unggah hingga 6 gambar sekaligus. Gambar pertama menjadi foto utama.</p>
                </div>

                <div>
                    <label for="images" class="block text-sm font-bold text-[#181818]">Tambah Gambar</label>
                    <input id="images" type="file" name="images[]" multiple
                           accept="image/jpeg,image/png,image/webp"
                           class="mt-1 w-full rounded-xl border border-[#181818]/20 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-[#124f53] file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                    @error('images')
                        <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('admin.products.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-full border-2 border-[#181818] px-5 py-2.5 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#333]">
                    {{ $editing ? 'Simpan Perubahan' : 'Simpan Produk' }}
                </button>
            </div>
        </form>

        @if ($editing && $images->isNotEmpty())
            <section class="rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <h2 class="text-lg font-black tracking-tight text-[#181818]">Gambar Tersimpan</h2>

                <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($images as $image)
                        <article class="overflow-hidden rounded-2xl border border-[#181818]/10">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->path) }}"
                                 alt="{{ $image->alt_text ?: $product->name }}" class="h-36 w-full object-cover">

                            <div class="flex flex-wrap items-center justify-between gap-2 p-3">
                                <span @class([
                                    'text-xs font-bold',
                                    'text-[#14747b]' => $image->is_primary,
                                    'text-[#181818]/50' => ! $image->is_primary,
                                ])>
                                    {{ $image->is_primary ? 'Foto utama' : 'Foto tambahan' }}
                                </span>

                                <div class="flex flex-wrap items-center gap-2">
                                    @unless ($image->is_primary)
                                        <form method="POST" action="{{ route('admin.products.images.primary', [$product, $image]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs font-bold text-[#124f53] hover:underline">Jadikan utama</button>
                                        </form>
                                    @endunless

                                    <form method="POST" action="{{ route('admin.products.images.destroy', [$product, $image]) }}"
                                          onsubmit="return confirm('Hapus gambar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-[#b3261e] hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
