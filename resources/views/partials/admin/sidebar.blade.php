{{--
    ADM - Sidebar navigasi dashboard (menu sesuai role)
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Layout induk (layouts/admin.blade.php) milik koordinator dan menaruh partial ini
              di atas konten, jadi menu dirender sebagai bar navigasi horizontal di dalam slot
              sidebar. Menu FIN (Order, Pelanggan, Pengeluaran, Invoice, Laporan, Pengaturan QRIS)
              ditambahkan bersama PRD-05; User Management hanya tampil untuk Owner.
              Warna mengikuti palet website publik (lihat resources/views/public): teal #14747b,
              #124f53, #58C0C8 dan ink #181818.
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
<nav class="bg-[#124f53] px-4 py-3 sm:px-6" aria-label="Navigasi dashboard">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-6 gap-y-3">
        @foreach ($menuGroups as $groupLabel => $links)
            <div class="flex flex-wrap items-center gap-1">
                <span class="mr-1 text-[11px] font-bold uppercase tracking-[0.14em] text-white/50">{{ $groupLabel }}</span>

                @foreach ($links as $link)
                    @php($isActive = request()->routeIs($link['active']))

                    <a href="{{ route($link['route']) }}"
                       @if ($isActive) aria-current="page" @endif
                       @class([
                           'rounded-full px-3 py-1.5 text-sm transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#58C0C8]',
                           'bg-[#58C0C8] font-bold text-[#181818]' => $isActive,
                           'font-medium text-white/85 hover:bg-white/10 hover:text-white' => ! $isActive,
                       ])>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</nav>
