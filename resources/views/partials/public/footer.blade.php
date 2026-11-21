{{--
    GLOBAL-CP-002 - Footer global
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Kontak harus konsisten dengan halaman Kontak (data dari site settings ADM).
    Status  : identitas situs, navigasi cepat, dan hak cipta diimplementasikan.
--}}

@php
    $footerNavigationItems = [
        ['label' => 'Beranda', 'route' => 'home', 'fallback' => '/'],
        ['label' => 'Katalog', 'route' => null, 'fallback' => null],
        ['label' => 'Galeri', 'route' => 'gallery', 'fallback' => '/galeri'],
        ['label' => 'Tentang Kami', 'route' => 'about', 'fallback' => '/tentang-kami'],
        ['label' => 'Ulasan & Kolaborasi', 'route' => 'reviews', 'fallback' => '/ulasan-kolaborasi'],
        ['label' => 'Kontak', 'route' => 'contact', 'fallback' => '/kontak'],
    ];

    $footerNavigationUrl = static function (array $item): string {
        if ($item['route'] === null) {
            return route('catalog.index');
        }

        return \Illuminate\Support\Facades\Route::has($item['route'])
            ? route($item['route'])
            : url($item['fallback']);
    };

    $siteTitle = config('app.name') ?: 'YOLOO Merch';
@endphp

<footer class="border-t border-zinc-200 bg-zinc-50">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-2 lg:px-8">
        <div>
            <a href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/') }}"
               class="text-lg font-bold text-zinc-950 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900">
                {{ $siteTitle }}
            </a>
        </div>

        <nav aria-label="Navigasi footer">
            <ul class="flex flex-wrap gap-x-5 gap-y-2">
                @foreach ($footerNavigationItems as $item)
                    <li>
                        <a href="{{ $footerNavigationUrl($item) }}"
                           class="text-sm text-zinc-700 underline-offset-4 hover:text-zinc-950 hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900">
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>

    <div class="border-t border-zinc-200">
        <p class="mx-auto max-w-7xl px-4 py-4 text-sm text-zinc-600 sm:px-6 lg:px-8">
            &copy; {{ now()->year }} {{ $siteTitle }}
        </p>
    </div>
</footer>
