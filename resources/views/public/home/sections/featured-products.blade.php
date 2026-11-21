{{--
    PAGE-CP-001 - Section 'featured-products' (Beranda)
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Partial khusus halaman ini; di-include dari file halaman utama.
    Status  : fallback katalog diimplementasikan; integrasi produk menunggu kontrak ADM/CAT.
--}}

<section aria-labelledby="home-featured-products-title" class="bg-zinc-50">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-16 lg:px-8">
        <div class="grid gap-6 rounded-3xl border border-zinc-200 bg-white p-6 sm:p-8 md:grid-cols-[minmax(0,1fr)_auto] md:items-center md:gap-10">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-zinc-500">
                    Katalog YOLOO Merch
                </p>
                <h2 id="home-featured-products-title" class="mt-3 text-2xl font-bold tracking-tight text-zinc-950 sm:text-3xl">
                    Jelajahi pilihan produk
                </h2>
                <p class="mt-3 text-base leading-7 text-zinc-700">
                    Kunjungi katalog untuk melihat produk dan kategori yang tersedia.
                </p>
            </div>

            {{-- Area kartu produk unggulan CAT ditambahkan setelah kontrak pemilihan dan data disepakati. --}}
            <div>
                <a href="{{ route('catalog.index') }}"
                   class="inline-flex w-full items-center justify-center rounded-lg bg-zinc-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 sm:w-auto">
                    Lihat Katalog
                </a>
            </div>
        </div>
    </div>
</section>
