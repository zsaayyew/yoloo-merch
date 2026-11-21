{{--
    PAGE-CP-001 - Section 'hero' (Beranda)
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Partial khusus halaman ini; di-include dari file halaman utama.
    Status  : diimplementasikan dengan komposisi text-led; aset hero resmi masih dibutuhkan.
--}}

<section aria-labelledby="home-hero-title" class="overflow-hidden bg-zinc-950 text-white">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-2 lg:px-8 lg:py-24">
        <div class="max-w-2xl">
            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-zinc-300">
                YOLOO Merch
            </p>

            <h1 id="home-hero-title" class="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl">
                Merchandise custom dan layanan cetak
            </h1>

            <p class="mt-6 max-w-xl text-base leading-7 text-zinc-300 sm:text-lg sm:leading-8">
                Jelajahi produk merchandise custom dan layanan cetak yang tersedia dalam katalog YOLOO Merch.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('catalog.index') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-semibold text-zinc-950 transition hover:bg-zinc-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                    Lihat Katalog
                </a>
                <a href="{{ route('about') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-zinc-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                    Tentang Kami
                </a>
            </div>
        </div>

        <div class="mx-auto w-full max-w-md lg:max-w-none" aria-hidden="true">
            <div class="relative aspect-square rounded-[2rem] border border-zinc-700 bg-zinc-900 p-5 sm:p-8">
                <div class="absolute inset-5 rounded-[1.5rem] border border-dashed border-zinc-700 sm:inset-8"></div>
                <div class="relative grid h-full grid-cols-2 grid-rows-2 gap-4 sm:gap-6">
                    <div class="rounded-3xl bg-zinc-800"></div>
                    <div class="flex items-center justify-center rounded-3xl border border-zinc-700 bg-zinc-950">
                        <div class="h-16 w-16 rounded-full border-[10px] border-zinc-600 sm:h-20 sm:w-20"></div>
                    </div>
                    <div class="flex items-center justify-center rounded-3xl border border-zinc-700 bg-zinc-950">
                        <div class="h-12 w-12 rotate-12 border-4 border-zinc-500 sm:h-16 sm:w-16"></div>
                    </div>
                    <div class="rounded-3xl bg-zinc-800"></div>
                </div>
            </div>
        </div>
    </div>
</section>
