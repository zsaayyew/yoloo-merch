{{--
    PAGE-ADM-003 - Form Testimoni
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
--}}
@extends('layouts.admin')

@section('title', $testimonial->exists ? 'Ubah Testimoni' : 'Tambah Testimoni')

@section('content')
    @php($editing = $testimonial->exists)

    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header
            :title="$editing ? 'Ubah Testimoni' : 'Tambah Testimoni'"
            description="Ulasan akan tampil di website hanya jika ditandai tampil." />

        <form method="POST"
              action="{{ $editing ? route('admin.content.testimonials.update', $testimonial) : route('admin.content.testimonials.store') }}"
              enctype="multipart/form-data" class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif

            <div class="grid gap-4 lg:grid-cols-2">
                @include('admin.partials.field-input', [
                    'name' => 'customer_name',
                    'label' => 'Nama Pelanggan',
                    'value' => $testimonial->customer_name,
                    'required' => true,
                ])

                @include('admin.partials.field-input', [
                    'name' => 'customer_role',
                    'label' => 'Keterangan (instansi/komunitas)',
                    'value' => $testimonial->customer_role,
                    'hint' => 'Contoh: Ketua BEM Universitas, Pemilik Kedai Kopi',
                ])
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                @include('admin.partials.field-select', [
                    'name' => 'rating',
                    'label' => 'Rating',
                    'value' => $testimonial->rating ?? 5,
                    'options' => [5 => '5 - Sangat baik', 4 => '4 - Baik', 3 => '3 - Cukup', 2 => '2 - Kurang', 1 => '1 - Buruk'],
                    'required' => true,
                ])

                @include('admin.partials.field-input', [
                    'name' => 'sort_order',
                    'label' => 'Urutan Tampil',
                    'value' => $testimonial->sort_order ?? 0,
                    'type' => 'number',
                    'hint' => 'Angka kecil tampil lebih dulu.',
                ])
            </div>

            @include('admin.partials.field-textarea', [
                'name' => 'quote',
                'label' => 'Isi Ulasan',
                'value' => $testimonial->quote,
                'rows' => 4,
                'required' => true,
            ])

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label for="photo" class="block text-sm font-bold text-[#181818]">Foto Pelanggan (opsional)</label>
                    <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp"
                           class="mt-1 w-full rounded-xl border border-[#181818]/20 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-[#124f53] file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                    @error('photo')
                        <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
                    @enderror
                </div>

                @include('admin.partials.field-checkbox', [
                    'name' => 'is_published',
                    'label' => 'Tampilkan di website',
                    'checked' => $testimonial->is_published,
                ])
            </div>

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('admin.content.testimonials.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-full border-2 border-[#181818] px-5 py-2.5 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#333]">
                    {{ $editing ? 'Simpan Perubahan' : 'Simpan Testimoni' }}
                </button>
            </div>
        </form>
    </div>
@endsection
