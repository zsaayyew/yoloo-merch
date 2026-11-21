{{--
    PAGE-CP-001 - Section 'intro' (Beranda)
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Partial khusus halaman ini; di-include dari file halaman utama.
    Status  : diimplementasikan dengan pengenalan layanan dan tautan ke katalog.
--}}

<section aria-labelledby="home-intro-title" class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-16 sm:px-6 sm:py-20 md:grid-cols-2 md:items-start lg:px-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-zinc-500">
                Pengenalan
            </p>
            <h2 id="home-intro-title" class="mt-3 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">
                Pilihan produk dapat dijelajahi melalui katalog
            </h2>
        </div>

        <div class="max-w-2xl text-base leading-7 text-zinc-700 sm:text-lg sm:leading-8">
            <p>
                YOLOO Merch menyediakan jasa pembuatan merchandise custom dan layanan cetak.
            </p>
            <p class="mt-4">
                Kunjungi katalog untuk menjelajahi produk yang tersedia.
            </p>
            <a href="{{ route('catalog.index') }}"
               class="mt-6 inline-flex items-center font-semibold text-zinc-950 underline decoration-zinc-400 underline-offset-4 hover:decoration-zinc-950 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900">
                Jelajahi Katalog
                <span class="sr-only"> YOLOO Merch</span>
            </a>
        </div>
    </div>
</section>
