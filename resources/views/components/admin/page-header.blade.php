{{--
    ADM - Judul halaman dashboard + tombol aksi
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Status  : diimplementasikan.
--}}
@props(['title', 'description' => null])

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-end justify-between gap-3']) }}>
    <div>
        <h1 class="text-2xl font-black tracking-tight text-[#181818] sm:text-3xl">{{ $title }}</h1>

        @if ($description)
            <p class="mt-1 max-w-2xl text-sm leading-6 text-[#181818]/60">{{ $description }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
