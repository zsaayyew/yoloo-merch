{{-- GLOBAL-CP-001: navigasi utama YOLOO Merch. --}}
@php
    $navigationItems = [
        ['label' => 'Beranda', 'route' => 'home'],
        ['label' => 'Katalog', 'route' => 'catalog.index'],
        ['label' => 'Galeri', 'route' => 'gallery'],
        ['label' => 'Tentang Kami', 'route' => 'about'],
        ['label' => 'Ulasan & Kolaborasi', 'route' => 'reviews'],
        ['label' => 'Kontak', 'route' => 'contact'],
    ];
    $currentRoute = request()->route()?->getName();
    $homeUrl = \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/');
@endphp

<header class="sticky top-0 z-40 border-b border-[#181818]/10 bg-white/95 shadow-sm backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-5 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ $homeUrl }}" class="shrink-0 rounded-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#14747b]" aria-label="YOLOO Merch — Beranda">
            <img src="{{ asset('images/brand/yoloo-logo-full.png') }}" alt="YOLOO Merch" width="330" height="96" class="h-11 w-auto sm:h-12">
        </a>

        <nav class="hidden lg:block" aria-label="Navigasi utama">
            <ul class="flex items-center gap-1">
                @foreach ($navigationItems as $item)
                    @php($available = \Illuminate\Support\Facades\Route::has($item['route']))
                    <li>
                        @if ($available)
                            <a href="{{ route($item['route']) }}" @if ($currentRoute === $item['route']) aria-current="page" @endif
                               class="rounded-full px-3 py-2 text-sm font-semibold transition hover:bg-[#58C0C8]/20 hover:text-[#124f53] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b] {{ $currentRoute === $item['route'] ? 'bg-[#58C0C8]/20 text-[#124f53]' : 'text-[#181818]' }}">
                                {{ $item['label'] }}
                            </a>
                        @elseif ($item['route'] !== 'catalog.index')
                            <span class="px-3 py-2 text-sm font-medium text-[#181818]/50">{{ $item['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <details class="relative lg:hidden" data-mobile-nav>
            <summary data-mobile-nav-toggle aria-controls="public-mobile-navigation"
                     class="flex cursor-pointer list-none items-center gap-2 rounded-full border border-[#181818]/15 px-4 py-2 text-sm font-bold text-[#181818] hover:border-[#14747b] hover:bg-[#58C0C8]/15 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b] [&::-webkit-details-marker]:hidden">
                <span data-mobile-nav-label>Menu</span>
                <span aria-hidden="true" class="text-lg leading-none">☰</span>
            </summary>
            <nav id="public-mobile-navigation" class="absolute right-0 top-[calc(100%+0.75rem)] z-50 w-[min(88vw,22rem)] rounded-2xl border border-[#181818]/10 bg-white p-3 shadow-xl" aria-label="Navigasi utama mobile">
                <ul class="flex flex-col gap-1">
                    @foreach ($navigationItems as $item)
                        @php($available = \Illuminate\Support\Facades\Route::has($item['route']))
                        @if ($available)
                            <li><a href="{{ route($item['route']) }}" @if ($currentRoute === $item['route']) aria-current="page" @endif
                                   class="block rounded-xl px-4 py-3 text-sm font-semibold {{ $currentRoute === $item['route'] ? 'bg-[#58C0C8]/20 text-[#124f53]' : 'text-[#181818] hover:bg-[#f8f8f8]' }} focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#14747b]">{{ $item['label'] }}</a></li>
                        @elseif ($item['route'] !== 'catalog.index')
                            <li><span class="block px-4 py-3 text-sm text-[#181818]/50">{{ $item['label'] }}</span></li>
                        @endif
                    @endforeach
                </ul>
            </nav>
        </details>
    </div>
</header>
