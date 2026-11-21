{{-- GLOBAL-CP-002: footer global; tautkan halaman kontak sampai ADM menyediakan kanal terverifikasi. --}}
@php
    $siteTitle = 'YOLOO Merch';
    $footerItems = [
        ['label' => 'Beranda', 'route' => 'home'],
        ['label' => 'Katalog', 'route' => 'catalog.index'],
        ['label' => 'Galeri', 'route' => 'gallery'],
        ['label' => 'Tentang Kami', 'route' => 'about'],
        ['label' => 'Ulasan & Kolaborasi', 'route' => 'reviews'],
        ['label' => 'Kontak', 'route' => 'contact'],
    ];
@endphp

<footer class="border-t border-[#181818]/10 bg-[#181818] text-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-[1.2fr_1fr_1fr] lg:px-8">
        <div>
            <a href="{{ route('home') }}" class="inline-flex rounded-md bg-white px-3 py-2 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#58C0C8]" aria-label="YOLOO Merch — Beranda">
                <img src="{{ asset('images/brand/yoloo-logo-full.png') }}" alt="YOLOO Merch" width="330" height="96" loading="lazy" class="h-10 w-auto">
            </a>
            <p class="mt-4 max-w-sm text-sm leading-6 text-white/75">Your Personal Merchandise Solution</p>
        </div>

        <nav aria-label="Navigasi footer">
            <h2 class="text-sm font-bold uppercase tracking-wider text-[#58C0C8]">Jelajahi</h2>
            <ul class="mt-4 grid grid-cols-2 gap-x-3 gap-y-2">
                @foreach ($footerItems as $item)
                    @if (\Illuminate\Support\Facades\Route::has($item['route']))
                        <li><a href="{{ route($item['route']) }}" class="text-sm text-white/80 underline-offset-4 hover:text-white hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#58C0C8]">{{ $item['label'] }}</a></li>
                    @endif
                @endforeach
            </ul>
        </nav>

        <div>
            <h2 class="text-sm font-bold uppercase tracking-wider text-[#58C0C8]">Kontak</h2>
            <p class="mt-4 text-sm leading-6 text-white/75">Informasi kanal komunikasi akan ditampilkan setelah dikonfirmasi.</p>
            @if (\Illuminate\Support\Facades\Route::has('contact'))<a href="{{ route('contact') }}" class="mt-3 inline-flex text-sm font-bold text-white underline decoration-[#58C0C8] decoration-2 underline-offset-4 hover:text-[#8ce0e4] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#58C0C8]">Halaman Kontak <span aria-hidden="true" class="ml-2">→</span></a>@endif
        </div>
    </div>
    <div class="border-t border-white/15">
        <p class="mx-auto max-w-7xl px-4 py-4 text-xs text-white/60 sm:px-6 lg:px-8">&copy; {{ now()->year }} {{ $siteTitle }}.</p>
    </div>
</footer>
