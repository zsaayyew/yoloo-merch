{{--
    GLOBAL-CP-001 - Header dan navigasi global (+ menu mobile)
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Urutan menu sesuai Master PRD. Katalog menuju CAT. Cart badge menunggu implementasi/koordinasi ORD. Tidak ada menu Cek Pesanan.
    Status  : navigasi desktop dan markup mobile diimplementasikan; modul JS menunggu import entry point bersama.
--}}

@php
    $navigationItems = [
        ['label' => 'Beranda', 'route' => 'home', 'fallback' => '/'],
        ['label' => 'Katalog', 'route' => null, 'fallback' => null],
        ['label' => 'Galeri', 'route' => 'gallery', 'fallback' => '/galeri'],
        ['label' => 'Tentang Kami', 'route' => 'about', 'fallback' => '/tentang-kami'],
        ['label' => 'Ulasan & Kolaborasi', 'route' => 'reviews', 'fallback' => '/ulasan-kolaborasi'],
        ['label' => 'Kontak', 'route' => 'contact', 'fallback' => '/kontak'],
    ];

    $navigationUrl = static function (array $item): string {
        if ($item['route'] === null) {
            return route('catalog.index');
        }

        return \Illuminate\Support\Facades\Route::has($item['route'])
            ? route($item['route'])
            : url($item['fallback']);
    };
@endphp

<header class="border-b border-zinc-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/') }}"
           class="shrink-0 text-xl font-bold tracking-tight text-zinc-950 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900"
           aria-label="YOLOO Merch — Beranda">
            YOLOO Merch
        </a>

        <nav class="hidden md:block" aria-label="Navigasi utama">
            <ul class="flex items-center gap-6">
                @foreach ($navigationItems as $item)
                    <li>
                        <a href="{{ $navigationUrl($item) }}"
                           class="text-sm font-medium text-zinc-700 hover:text-zinc-950 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900">
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <button type="button"
                class="inline-flex items-center justify-center rounded-lg border border-zinc-300 px-3 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 md:hidden"
                data-mobile-nav-toggle
                aria-expanded="false"
                aria-controls="public-mobile-navigation">
            <span data-mobile-nav-label>Menu</span>
        </button>
    </div>

    <nav id="public-mobile-navigation" class="border-t border-zinc-200 px-4 py-3 sm:px-6 md:hidden" aria-label="Navigasi utama mobile" hidden>
        <ul class="mx-auto flex max-w-7xl flex-col gap-1">
            @foreach ($navigationItems as $item)
                <li>
                    <a href="{{ $navigationUrl($item) }}"
                       class="block rounded-lg px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100 hover:text-zinc-950 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900">
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</header>
