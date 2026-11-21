{{--
    PAGE-CP-001 - Section 'services' (Beranda)
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Partial khusus halaman ini; di-include dari file halaman utama.
    Status  : diimplementasikan berdasarkan positioning layanan di Master PRD.
--}}

<section aria-labelledby="home-services-title" class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-zinc-500">
                Layanan
            </p>
            <h2 id="home-services-title" class="mt-3 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">
                Merchandise custom dan layanan cetak
            </h2>
            <p class="mt-4 text-base leading-7 text-zinc-700 sm:text-lg sm:leading-8">
                YOLOO Merch menyediakan jasa pembuatan merchandise custom dan layanan cetak berdasarkan produk yang tersedia dalam katalog.
            </p>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2">
            <article class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6 sm:p-8">
                <h3 class="text-xl font-semibold tracking-tight text-zinc-950">
                    Pembuatan merchandise custom
                </h3>
                <p class="mt-3 leading-7 text-zinc-700">
                    Jelajahi pilihan produk merchandise custom melalui katalog.
                </p>
            </article>

            <article class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6 sm:p-8">
                <h3 class="text-xl font-semibold tracking-tight text-zinc-950">
                    Layanan cetak
                </h3>
                <p class="mt-3 leading-7 text-zinc-700">
                    Layanan cetak tersedia berdasarkan produk yang tercantum dalam katalog.
                </p>
            </article>
        </div>

        <a href="{{ route('catalog.index') }}"
           class="mt-8 inline-flex items-center justify-center rounded-lg bg-zinc-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900">
            Jelajahi Katalog
        </a>
    </div>
</section>
