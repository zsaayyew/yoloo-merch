{{--
    PAGE-ADM-003 - Testimoni
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/content/testimonials (ADM-FR-007).
--}}
@extends('layouts.admin')

@section('title', 'Testimoni')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Testimoni"
            description="Ulasan pelanggan yang ditampilkan pada bagian ulasan di website.">
            <x-slot:actions>
                <a href="{{ route('admin.content.testimonials.create') }}"
                   class="inline-flex min-h-10 items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#333]">
                    Tambah Testimoni
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <section class="overflow-hidden rounded-3xl border border-[#181818]/10 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#181818]/10 text-sm">
                    <thead class="bg-[#f8f8f8]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Pelanggan</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Ulasan</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Rating</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-[#181818]/50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#181818]/5">
                        @forelse ($testimonials as $testimonial)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($testimonial->photo_path)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($testimonial->photo_path) }}"
                                                 alt="Foto {{ $testimonial->customer_name }}"
                                                 class="h-10 w-10 rounded-full object-cover">
                                        @endif
                                        <div>
                                            <p class="font-bold text-[#181818]">{{ $testimonial->customer_name }}</p>
                                            <p class="text-xs text-[#181818]/50">{{ $testimonial->customer_role ?: 'Pelanggan' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="max-w-sm px-4 py-3 text-[#181818]/70">{{ \Illuminate\Support\Str::limit($testimonial->quote, 100) }}</td>
                                <td class="px-4 py-3 font-bold text-[#14747b]">{{ $testimonial->rating }}/5</td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                        'bg-[#DDF3F0] text-[#124f53]' => $testimonial->is_published,
                                        'bg-[#181818]/10 text-[#181818]/60' => ! $testimonial->is_published,
                                    ])>
                                        {{ $testimonial->is_published ? 'Tampil' : 'Disembunyikan' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center justify-end gap-3">
                                        <form method="POST" action="{{ route('admin.content.testimonials.publish', $testimonial) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm font-bold text-[#124f53] hover:underline">
                                                {{ $testimonial->is_published ? 'Sembunyikan' : 'Tampilkan' }}
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.content.testimonials.edit', $testimonial) }}"
                                           class="text-sm font-bold text-[#181818] hover:underline">Ubah</a>

                                        <form method="POST" action="{{ route('admin.content.testimonials.destroy', $testimonial) }}"
                                              onsubmit="return confirm('Hapus testimoni ini?')">
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
                                    Belum ada testimoni. Tambahkan ulasan pertama pelanggan Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
