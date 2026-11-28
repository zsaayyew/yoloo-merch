{{--
    PAGE-ADM-003 - Kategori Galeri
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/content/gallery-categories (ADM-FR-007).
--}}
@extends('layouts.admin')

@section('title', 'Kategori Galeri')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Kategori Galeri"
            description="Kelompokkan dokumentasi galeri agar mudah ditelusuri pengunjung website.">
            <x-slot:actions>
                <a href="{{ route('admin.content.gallery-categories.create') }}"
                   class="inline-flex min-h-10 items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#333]">
                    Tambah Kategori
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <section class="overflow-hidden rounded-3xl border border-[#181818]/10 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#181818]/10 text-sm">
                    <thead class="bg-[#f8f8f8]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Slug</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Item</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-[#181818]/50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#181818]/5">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-bold text-[#181818]">{{ $category->name }}</p>
                                    <p class="text-xs text-[#181818]/50">{{ \Illuminate\Support\Str::limit($category->description, 80) }}</p>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-[#181818]/60">{{ $category->slug }}</td>
                                <td class="px-4 py-3 text-[#181818]/70">{{ $category->items_count }}</td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                        'bg-[#DDF3F0] text-[#124f53]' => $category->is_active,
                                        'bg-[#181818]/10 text-[#181818]/60' => ! $category->is_active,
                                    ])>
                                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center justify-end gap-3">
                                        <a href="{{ route('admin.content.gallery-categories.edit', $category) }}"
                                           class="text-sm font-bold text-[#181818] hover:underline">Ubah</a>

                                        <form method="POST" action="{{ route('admin.content.gallery-categories.destroy', $category) }}"
                                              onsubmit="return confirm('Hapus kategori galeri ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-bold text-[#b3261e] hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-[#181818]/50">
                                    Belum ada kategori galeri.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
