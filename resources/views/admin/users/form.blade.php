{{--
    PAGE-ADM-006 - Form Akun Admin
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
--}}
@extends('layouts.admin')

@section('title', $user->exists ? 'Ubah Akun Admin' : 'Tambah Akun Admin')

@section('content')
    @php($editing = $user->exists)

    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header
            :title="$editing ? 'Ubah Akun Admin' : 'Tambah Akun Admin'"
            description="Kata sandi minimal 8 karakter. Biarkan kosong saat mengubah bila tidak ingin menggantinya." />

        <form method="POST"
              action="{{ $editing ? route('admin.users.update', $user) : route('admin.users.store') }}"
              class="space-y-4 rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif

            <div class="grid gap-4 lg:grid-cols-2">
                @include('admin.partials.field-input', [
                    'name' => 'name',
                    'label' => 'Nama',
                    'value' => $user->name,
                    'required' => true,
                ])

                @include('admin.partials.field-input', [
                    'name' => 'email',
                    'label' => 'Email',
                    'value' => $user->email,
                    'type' => 'email',
                    'required' => true,
                ])

                @include('admin.partials.field-select', [
                    'name' => 'role_id',
                    'label' => 'Role',
                    'value' => $user->role_id,
                    'options' => $roles->pluck('label', 'id')->all(),
                    'placeholder' => 'Pilih role',
                    'required' => true,
                ])

                @include('admin.partials.field-input', [
                    'name' => 'password',
                    'label' => $editing ? 'Kata Sandi Baru (opsional)' : 'Kata Sandi',
                    'value' => '',
                    'type' => 'password',
                    'required' => ! $editing,
                ])

                @include('admin.partials.field-input', [
                    'name' => 'password_confirmation',
                    'label' => 'Ulangi Kata Sandi',
                    'value' => '',
                    'type' => 'password',
                    'required' => ! $editing,
                ])
            </div>

            @include('admin.partials.field-checkbox', [
                'name' => 'is_active',
                'label' => 'Akun aktif dan boleh masuk',
                'checked' => $user->is_active,
            ])

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-full border-2 border-[#181818] px-5 py-2.5 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#333]">
                    {{ $editing ? 'Simpan Perubahan' : 'Simpan Akun' }}
                </button>
            </div>
        </form>
    </div>
@endsection
