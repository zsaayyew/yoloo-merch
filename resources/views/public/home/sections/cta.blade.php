{{--
    PAGE-CP-001 - Section 'cta' (Beranda)
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Partial khusus halaman ini; di-include dari file halaman utama.
    Status  : diimplementasikan dengan tautan katalog dan Tentang Kami.
--}}

<section aria-labelledby="home-cta-title" class="bg-zinc-950 text-white">
    <div class="mx-auto flex max-w-7xl flex-col gap-8 px-4 py-14 sm:px-6 sm:py-16 md:flex-row md:items-center md:justify-between lg:px-8">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-zinc-300">
                YOLOO Merch
            </p>
            <h2 id="home-cta-title" class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                Lanjutkan menjelajahi pilihan merchandise
            </h2>
            <p class="mt-4 text-base leading-7 text-zinc-300 sm:text-lg sm:leading-8">
                Lihat produk yang tersedia di katalog atau kenali lebih jauh YOLOO Merch.
            </p>
        </div>

        <div class="flex shrink-0 flex-col gap-3 sm:flex-row md:flex-col lg:flex-row">
            <a href="{{ route('catalog.index') }}"
               class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-semibold text-zinc-950 transition hover:bg-zinc-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                Jelajahi Katalog
            </a>
            <a href="{{ route('about') }}"
               class="inline-flex items-center justify-center rounded-lg border border-zinc-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                Tentang Kami
            </a>
        </div>
    </div>
</section>
