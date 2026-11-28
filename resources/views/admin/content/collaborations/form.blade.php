{{--
    PAGE-ADM-003 - Form Kolaborasi
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
--}}
@extends('layouts.admin')

@section('title', $collaboration->exists ? 'Ubah Kolaborasi' : 'Tambah Kolaborasi')

@section('content')
    @php($editing = $collaboration->exists)

    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header
            :title="$editing ? 'Ubah Kolaborasi' : 'Tambah Kolaborasi'"
            description="Nama mitra, logo, dan keterangan singkat kerja sama." />

        <form method="POST"
              action="{{ $editing ? route('admin.content.collaborations.update', $collaboration) : route('admin.content.collaborations.store') }}"
              enctype="multipart/form-data" class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif

            <div class="grid gap-4 lg:grid-cols-2">
                @include('admin.partials.field-input', [
                    'name' => 'partner_name',
                    'label' => 'Nama Mitra',
                    'value' => $collaboration->partner_name,
                    'required' => true,
                ])

                @include('admin.partials.field-input', [
                    'name' => 'website_url',
                    'label' => 'Tautan Website (opsional)',
                    'value' => $collaboration->website_url,
                    'type' => 'url',
                ])

                @include('admin.partials.field-input', [
                    'name' => 'sort_order',
                    'label' => 'Urutan Tampil',
                    'value' => $collaboration->sort_order ?? 0,
                    'type' => 'number',
                ])
            </div>

            @include('admin.partials.field-textarea', [
                'name' => 'description',
                'label' => 'Keterangan',
                'value' => $collaboration->description,
                'rows' => 3,
            ])

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label for="logo" class="block text-sm font-bold text-[#181818]">Logo Mitra (opsional)</label>
                    <input id="logo" type="file" name="logo" accept="image/jpeg,image/png,image/webp"
                           class="mt-1 w-full rounded-xl border border-[#181818]/20 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-[#124f53] file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                    @error('logo')
                        <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
                    @enderror
                </div>

                @include('admin.partials.field-checkbox', [
                    'name' => 'is_published',
                    'label' => 'Tampilkan di website',
                    'checked' => $collaboration->is_published,
                ])
            </div>

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('admin.content.collaborations.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-full border-2 border-[#181818] px-5 py-2.5 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#333]">
                    {{ $editing ? 'Simpan Perubahan' : 'Simpan Kolaborasi' }}
                </button>
            </div>
        </form>
    </div>
@endsection
