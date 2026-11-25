{{--
    ADM - Sidebar navigasi dashboard (menu sesuai role)
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Layout induk (layouts/admin.blade.php) milik koordinator dan menaruh partial ini
              di atas konten, jadi menu dirender sebagai bar navigasi horizontal di dalam slot
              sidebar. Menu FIN (Order, Pelanggan, Pengeluaran, Invoice, Laporan, Pengaturan QRIS)
              ditambahkan bersama PRD-05; User Management hanya tampil untuk Owner.
--}}
@php
    $menuGroups = [
        'Utama' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
        ],
        'Konten' => [
            ['label' => 'Beranda', 'route' => 'admin.content.homepage.edit', 'active' => 'admin.content.homepage.*'],
            ['label' => 'Tentang', 'route' => 'admin.content.about.edit', 'active' => 'admin.content.about.*'],
            ['label' => 'Profil Usaha', 'route' => 'admin.content.settings.edit', 'active' => 'admin.content.settings.*'],
            ['label' => 'Testimoni', 'route' => 'admin.content.testimonials.index', 'active' => 'admin.content.testimonials.*'],
            ['label' => 'Kolaborasi', 'route' => 'admin.content.collaborations.index', 'active' => 'admin.content.collaborations.*'],
            ['label' => 'Galeri', 'route' => 'admin.content.gallery.index', 'active' => 'admin.content.gallery.*'],
            ['label' => 'Kategori Galeri', 'route' => 'admin.content.gallery-categories.index', 'active' => 'admin.content.gallery-categories.*'],
        ],
        'Katalog' => [
            ['label' => 'Kategori', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*'],
            ['label' => 'Produk', 'route' => 'admin.products.index', 'active' => 'admin.products.*'],
            ['label' => 'Harga', 'route' => 'admin.pricing.index', 'active' => 'admin.pricing.*'],
        ],
        'Lainnya' => [
            ['label' => 'Media', 'route' => 'admin.media.index', 'active' => 'admin.media.*'],
        ],
    ];

    if (auth()->user()?->isOwner()) {
        $menuGroups['Lainnya'][] = ['label' => 'Pengguna', 'route' => 'admin.users.index', 'active' => 'admin.users.*'];
    }
@endphp
<nav class="border-b border-gray-200 bg-white px-6 py-2" aria-label="Navigasi dashboard">
    <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
        @foreach ($menuGroups as $groupLabel => $links)
            <div class="flex flex-wrap items-center gap-1">
                <span class="mr-1 text-[11px] font-semibold uppercase tracking-wide text-gray-400">{{ $groupLabel }}</span>

                @foreach ($links as $link)
                    @php($isActive = request()->routeIs($link['active']))

                    <a href="{{ route($link['route']) }}"
                       @if ($isActive) aria-current="page" @endif
                       @class([
                           'rounded-md px-2.5 py-1.5 text-sm transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900',
                           'bg-gray-900 font-medium text-white' => $isActive,
                           'text-gray-600 hover:bg-gray-100 hover:text-gray-900' => ! $isActive,
                       ])>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</nav>
