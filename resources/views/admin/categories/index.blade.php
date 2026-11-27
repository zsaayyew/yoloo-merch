{{--
    PAGE-ADM-004 - Kategori Produk
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/categories (ADM-FR-004).
--}}
@extends('layouts.admin')

@section('title', 'Kategori Produk')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Kategori Produk"
            description="Kategori aktif tampil sebagai filter katalog di website publik.">
            <x-slot:actions>
                <a href="{{ route('admin.categories.create') }}"
                   class="inline-flex min-h-10 items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#333]">
                    Tambah Kategori
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-wrap items-end gap-2">
            <div class="flex-1 min-w-[14rem]">
                <label for="q" class="block text-sm font-bold text-[#181818]">Cari kategori</label>
                <input id="q" type="search" name="q" value="{{ $search }}"
                       class="mt-1 w-full rounded-xl border border-[#181818]/20 bg-white px-3 py-2 text-sm">
            </div>
            <button type="submit"
                    class="inline-flex min-h-10 items-center justify-center rounded-full border-2 border-[#181818] px-4 py-2 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                Cari
            </button>
        </form>

        <section class="overflow-hidden rounded-3xl border border-[#181818]/10 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#181818]/10 text-sm">
                    <thead class="bg-[#f8f8f8]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Slug</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Produk</th>
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
                                <td class="px-4 py-3 text-[#181818]/70">{{ $category->products_count }}</td>
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
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="text-sm font-bold text-[#181818] hover:underline">Ubah</a>

                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                              onsubmit="return confirm('Hapus kategori ini?')">
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
                                    Belum ada kategori produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
