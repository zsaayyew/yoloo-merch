{{--
    PAGE-ADM-003 - Profil Usaha
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/content/settings (ADM-FR-007). Nilai dipakai halaman kontak publik.
--}}
@extends('layouts.admin')

@section('title', 'Profil Usaha')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Profil Usaha"
            description="Identitas usaha dan kanal kontak yang tampil di website publik." />

        <form method="POST" action="{{ route('admin.content.settings.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <section class="rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <h2 class="text-lg font-black tracking-tight text-[#181818]">Identitas</h2>

                <div class="mt-4 grid gap-4 lg:grid-cols-2">
                    @include('admin.partials.field-input', [
                        'name' => 'settings.business_name',
                        'label' => $fields['business_name']['label'],
                        'value' => $values['business_name'],
                        'required' => true,
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'settings.tagline',
                        'label' => $fields['tagline']['label'],
                        'value' => $values['tagline'],
                    ])
                </div>

                <div class="mt-4">
                    @include('admin.partials.field-textarea', [
                        'name' => 'settings.description',
                        'label' => $fields['description']['label'],
                        'value' => $values['description'],
                        'rows' => 3,
                    ])
                </div>

                <div class="mt-4">
                    @include('admin.partials.field-textarea', [
                        'name' => 'settings.address',
                        'label' => $fields['address']['label'],
                        'value' => $values['address'],
                        'rows' => 3,
                    ])
                </div>
            </section>

            <section class="rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
                <h2 class="text-lg font-black tracking-tight text-[#181818]">Kontak &amp; Jam Buka</h2>

                <div class="mt-4 grid gap-4 lg:grid-cols-2">
                    @include('admin.partials.field-input', [
                        'name' => 'settings.phone',
                        'label' => $fields['phone']['label'],
                        'value' => $values['phone'],
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'settings.whatsapp',
                        'label' => $fields['whatsapp']['label'],
                        'value' => $values['whatsapp'],
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'settings.email',
                        'label' => $fields['email']['label'],
                        'value' => $values['email'],
                        'type' => 'email',
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'settings.instagram',
                        'label' => $fields['instagram']['label'],
                        'value' => $values['instagram'],
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'settings.operating_hours',
                        'label' => $fields['operating_hours']['label'],
                        'value' => $values['operating_hours'],
                        'hint' => 'Contoh: Senin-Sabtu 09.00-17.00 WIB',
                    ])

                    @include('admin.partials.field-input', [
                        'name' => 'settings.maps_url',
                        'label' => $fields['maps_url']['label'],
                        'value' => $values['maps_url'],
                        'type' => 'url',
                    ])
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-full bg-[#181818] px-6 py-2.5 text-sm font-bold text-white transition hover:bg-[#333] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    Simpan Profil Usaha
                </button>
            </div>
        </form>
    </div>
@endsection
