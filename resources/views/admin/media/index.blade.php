{{--
    PAGE-ADM-005 - Pustaka Media
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/media (ADM-FR-008). JPG/PNG/WEBP/PDF maks 5 MB per berkas.
--}}
@extends('layouts.admin')

@section('title', 'Media')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Pustaka Media"
            description="Kumpulan gambar dan dokumen yang dipakai konten website." />

        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data"
              class="space-y-3 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
            @csrf

            <div>
                <label for="files" class="block text-sm font-bold text-[#181818]">Unggah Berkas</label>
                <input id="files" type="file" name="files[]" multiple
                       accept="image/jpeg,image/png,image/webp,application/pdf"
                       class="mt-1 w-full rounded-xl border border-[#181818]/20 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-[#124f53] file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                <p class="mt-1 text-xs text-[#181818]/50">Maksimal 10 berkas sekaligus, 5 MB per berkas.</p>
                @error('files')
                    <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
                @enderror
                @error('files.*')
                    <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex min-h-10 items-center justify-center rounded-full bg-[#181818] px-5 py-2 text-sm font-bold text-white hover:bg-[#333]">
                    Unggah ke Pustaka
                </button>
            </div>
        </form>

        @if ($media->isEmpty())
            <p class="rounded-3xl border border-dashed border-[#181818]/20 bg-white px-4 py-10 text-center text-sm text-[#181818]/50">
                Pustaka media masih kosong.
            </p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($media as $item)
                    <article class="flex flex-col overflow-hidden rounded-3xl border border-[#181818]/10 bg-white">
                        @if ($item->isImage())
                            <img src="{{ $item->url() }}" alt="{{ $item->alt_text ?: $item->original_name }}"
                                 class="h-36 w-full object-cover">
                        @else
                            <div class="flex h-36 items-center justify-center bg-[#181818]/5 text-xs font-bold uppercase tracking-wider text-[#181818]/50">
                                Dokumen PDF
                            </div>
                        @endif

                        <div class="flex flex-1 flex-col gap-2 p-4">
                            <p class="truncate text-sm font-bold text-[#181818]" title="{{ $item->original_name }}">
                                {{ $item->original_name }}
                            </p>
                            <p class="text-xs text-[#181818]/50">{{ $item->sizeForHumans() }} &middot; {{ $item->created_at?->translatedFormat('j M Y') }}</p>

                            <form method="POST" action="{{ route('admin.media.update', $item) }}" class="flex items-end gap-2">
                                @csrf
                                @method('PATCH')
                                <div class="flex-1">
                                    <label for="alt-{{ $item->id }}" class="block text-xs font-bold text-[#181818]">Teks alternatif</label>
                                    <input id="alt-{{ $item->id }}" type="text" name="alt_text" value="{{ $item->alt_text }}"
                                           class="mt-1 w-full rounded-lg border border-[#181818]/20 px-2 py-1 text-xs">
                                </div>
                                <button type="submit" class="text-xs font-bold text-[#124f53] hover:underline">Simpan</button>
                            </form>

                            <div class="mt-auto flex items-center justify-between gap-2 pt-2">
                                <a href="{{ $item->url() }}" target="_blank" rel="noopener"
                                   class="text-xs font-bold text-[#181818] hover:underline">Buka berkas</a>

                                <form method="POST" action="{{ route('admin.media.destroy', $item) }}"
                                      onsubmit="return confirm('Hapus berkas ini dari pustaka?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-[#b3261e] hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div>{{ $media->links() }}</div>
        @endif
    </div>
@endsection
