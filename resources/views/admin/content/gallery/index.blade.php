{{--
    PAGE-ADM-003 - Galeri
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/content/gallery (ADM-FR-007).
--}}
@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Galeri"
            description="Foto dokumentasi produk dan kegiatan yang tampil di halaman galeri.">
            <x-slot:actions>
                <a href="{{ route('admin.content.gallery.create') }}"
                   class="inline-flex min-h-10 items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#333]">
                    Tambah Item Galeri
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        @if ($items->isEmpty())
            <p class="rounded-3xl border border-dashed border-[#181818]/20 bg-white px-4 py-10 text-center text-sm text-[#181818]/50">
                Belum ada item galeri.
            </p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($items as $item)
                    <article class="overflow-hidden rounded-3xl border border-[#181818]/10 bg-white">
                        @if ($item->image_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path) }}"
                                 alt="{{ $item->title }}" class="h-40 w-full object-cover">
                        @else
                            <div class="flex h-40 items-center justify-center bg-[#181818]/5 text-xs font-bold uppercase tracking-wider text-[#181818]/40">
                                Tanpa gambar
                            </div>
                        @endif

                        <div class="space-y-2 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <h2 class="font-bold text-[#181818]">{{ $item->title }}</h2>
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                    'bg-[#DDF3F0] text-[#124f53]' => $item->is_published,
                                    'bg-[#181818]/10 text-[#181818]/60' => ! $item->is_published,
                                ])>
                                    {{ $item->is_published ? 'Tampil' : 'Disembunyikan' }}
                                </span>
                            </div>

                            <p class="text-xs text-[#181818]/50">
                                {{ $item->category?->name ?? 'Tanpa kategori' }} &middot; urutan {{ $item->sort_order }}
                            </p>

                            @if ($item->caption)
                                <p class="text-sm text-[#181818]/70">{{ \Illuminate\Support\Str::limit($item->caption, 120) }}</p>
                            @endif

                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <form method="POST" action="{{ route('admin.content.gallery.publish', $item) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-sm font-bold text-[#124f53] hover:underline">
                                        {{ $item->is_published ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                </form>

                                <a href="{{ route('admin.content.gallery.edit', $item) }}"
                                   class="text-sm font-bold text-[#181818] hover:underline">Ubah</a>

                                <form method="POST" action="{{ route('admin.content.gallery.destroy', $item) }}"
                                      onsubmit="return confirm('Hapus item galeri ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-bold text-[#b3261e] hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection
