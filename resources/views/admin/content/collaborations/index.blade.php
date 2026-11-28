{{--
    PAGE-ADM-003 - Kolaborasi
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/content/collaborations (ADM-FR-007).
--}}
@extends('layouts.admin')

@section('title', 'Kolaborasi')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Kolaborasi"
            description="Dokumentasi kerja sama dengan komunitas, instansi, atau brand.">
            <x-slot:actions>
                <a href="{{ route('admin.content.collaborations.create') }}"
                   class="inline-flex min-h-10 items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#333]">
                    Tambah Kolaborasi
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <section class="overflow-hidden rounded-3xl border border-[#181818]/10 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#181818]/10 text-sm">
                    <thead class="bg-[#f8f8f8]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Mitra</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Keterangan</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-[#181818]/50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#181818]/5">
                        @forelse ($collaborations as $collaboration)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($collaboration->logo_path)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($collaboration->logo_path) }}"
                                                 alt="Logo {{ $collaboration->partner_name }}"
                                                 class="h-10 w-10 rounded-xl object-contain">
                                        @endif
                                        <div>
                                            <p class="font-bold text-[#181818]">{{ $collaboration->partner_name }}</p>
                                            @if ($collaboration->website_url)
                                                <p class="text-xs text-[#181818]/50">{{ $collaboration->website_url }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="max-w-sm px-4 py-3 text-[#181818]/70">
                                    {{ \Illuminate\Support\Str::limit($collaboration->description, 100) ?: '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                        'bg-[#DDF3F0] text-[#124f53]' => $collaboration->is_published,
                                        'bg-[#181818]/10 text-[#181818]/60' => ! $collaboration->is_published,
                                    ])>
                                        {{ $collaboration->is_published ? 'Tampil' : 'Disembunyikan' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center justify-end gap-3">
                                        <form method="POST" action="{{ route('admin.content.collaborations.publish', $collaboration) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm font-bold text-[#124f53] hover:underline">
                                                {{ $collaboration->is_published ? 'Sembunyikan' : 'Tampilkan' }}
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.content.collaborations.edit', $collaboration) }}"
                                           class="text-sm font-bold text-[#181818] hover:underline">Ubah</a>

                                        <form method="POST" action="{{ route('admin.content.collaborations.destroy', $collaboration) }}"
                                              onsubmit="return confirm('Hapus kolaborasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-bold text-[#b3261e] hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-[#181818]/50">
                                    Belum ada kolaborasi yang dicatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
