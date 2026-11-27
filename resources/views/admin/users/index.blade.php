{{--
    PAGE-ADM-006 - Manajemen Akun Admin
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/users. Owner saja (Admin -> 403). (ADM-FR-009)
--}}
@extends('layouts.admin')

@section('title', 'Manajemen Akun Admin')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Manajemen Akun Admin"
            description="Akun Admin dapat mengelola konten dan katalog, tetapi tidak dapat mengelola akun.">
            <x-slot:actions>
                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex min-h-10 items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#333]">
                    Tambah Akun
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <section class="overflow-hidden rounded-3xl border border-[#181818]/10 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#181818]/10 text-sm">
                    <thead class="bg-[#f8f8f8]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-[#181818]/50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#181818]/5">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-3 font-bold text-[#181818]">
                                    {{ $user->name }}
                                    @if ($user->id === auth()->id())
                                        <span class="ml-1 text-xs font-bold text-[#14747b]">(Anda)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-[#181818]/70">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-[#181818]/70">{{ $user->role?->label ?? 'Tanpa role' }}</td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                        'bg-[#DDF3F0] text-[#124f53]' => $user->is_active,
                                        'bg-[#181818]/10 text-[#181818]/60' => ! $user->is_active,
                                    ])>
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center justify-end gap-3">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="text-sm font-bold text-[#181818] hover:underline">Ubah</a>

                                        @if ($user->id !== auth()->id() && ! $user->isOwner())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-bold text-[#b3261e] hover:underline">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
