{{--
    PAGE-ADM-003 - Form Item Galeri
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
--}}
@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Item Galeri' : 'Tambah Item Galeri')

@section('content')
    @php($editing = $item->exists)

    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header
            :title="$editing ? 'Ubah Item Galeri' : 'Tambah Item Galeri'"
            description="Unggah foto dokumentasi dan pilih kategori galerinya." />

        <form method="POST"
              action="{{ $editing ? route('admin.content.gallery.update', $item) : route('admin.content.gallery.store') }}"
              enctype="multipart/form-data" class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif

            <div class="grid gap-4 lg:grid-cols-2">
                @include('admin.partials.field-input', [
                    'name' => 'title',
                    'label' => 'Judul',
                    'value' => $item->title,
                    'required' => true,
                ])

                @include('admin.partials.field-select', [
                    'name' => 'gallery_category_id',
                    'label' => 'Kategori',
                    'value' => $item->gallery_category_id,
                    'options' => $categories->pluck('name', 'id')->all(),
                    'placeholder' => 'Tanpa kategori',
                ])

                @include('admin.partials.field-input', [
                    'name' => 'sort_order',
                    'label' => 'Urutan Tampil',
                    'value' => $item->sort_order ?? 0,
                    'type' => 'number',
                ])
            </div>

            @include('admin.partials.field-textarea', [
                'name' => 'caption',
                'label' => 'Keterangan Foto',
                'value' => $item->caption,
                'rows' => 3,
            ])

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label for="image" class="block text-sm font-bold text-[#181818]">Foto</label>
                    <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp"
                           class="mt-1 w-full rounded-xl border border-[#181818]/20 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-[#124f53] file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                    <p class="mt-1 text-xs text-[#181818]/50">JPG, PNG, atau WEBP maksimal 4 MB.</p>
                    @error('image')
                        <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
                    @enderror
                </div>

                @include('admin.partials.field-checkbox', [
                    'name' => 'is_published',
                    'label' => 'Tampilkan di website',
                    'checked' => $item->is_published,
                ])
            </div>

            @if ($item->image_path)
                <div>
                    <p class="text-sm font-bold text-[#181818]">Foto saat ini</p>
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path) }}"
                         alt="{{ $item->title }}" class="mt-1 h-40 rounded-xl object-cover">
                </div>
            @endif

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('admin.content.gallery.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-full border-2 border-[#181818] px-5 py-2.5 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#333]">
                    {{ $editing ? 'Simpan Perubahan' : 'Simpan Item' }}
                </button>
            </div>
        </form>
    </div>
@endsection
