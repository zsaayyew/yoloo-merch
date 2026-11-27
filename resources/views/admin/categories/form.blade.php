{{--
    PAGE-ADM-004 - Form Kategori Produk
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
--}}
@extends('layouts.admin')

@section('title', $category->exists ? 'Ubah Kategori' : 'Tambah Kategori')

@section('content')
    @php($editing = $category->exists)

    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header
            :title="$editing ? 'Ubah Kategori' : 'Tambah Kategori'"
            description="Slug dibuat otomatis dari nama kategori." />

        <form method="POST"
              action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
              class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif

            <div class="grid gap-4 lg:grid-cols-2">
                @include('admin.partials.field-input', [
                    'name' => 'name',
                    'label' => 'Nama Kategori',
                    'value' => $category->name,
                    'required' => true,
                ])

                @include('admin.partials.field-input', [
                    'name' => 'sort_order',
                    'label' => 'Urutan Tampil',
                    'value' => $category->sort_order ?? 0,
                    'type' => 'number',
                ])
            </div>

            @include('admin.partials.field-textarea', [
                'name' => 'description',
                'label' => 'Keterangan',
                'value' => $category->description,
                'rows' => 3,
            ])

            @include('admin.partials.field-checkbox', [
                'name' => 'is_active',
                'label' => 'Aktif dan tampil di website',
                'checked' => $category->is_active,
            ])

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-full border-2 border-[#181818] px-5 py-2.5 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#333]">
                    {{ $editing ? 'Simpan Perubahan' : 'Simpan Kategori' }}
                </button>
            </div>
        </form>
    </div>
@endsection
