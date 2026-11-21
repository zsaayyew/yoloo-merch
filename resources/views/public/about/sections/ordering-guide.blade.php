{{--
    PAGE-CP-002 - Section 'ordering-guide' (Tentang Kami)
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Panduan awal untuk menjelajahi katalog dan detail produk.
    Status  : diimplementasikan; integrasi jalur pemesanan menunggu ORD.
--}}

<section aria-labelledby="about-ordering-guide-title" class="bg-zinc-50">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-zinc-500">
                Panduan Awal
            </p>
            <h2 id="about-ordering-guide-title" class="mt-3 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">
                Mulai dari katalog
            </h2>
            <p class="mt-4 text-base leading-7 text-zinc-700 sm:text-lg sm:leading-8">
                Jelajahi pilihan yang tersedia dan baca informasi produk untuk mengetahui opsi yang dapat dipertimbangkan.
            </p>
        </div>

        <ol class="mt-8 grid gap-4 md:grid-cols-3">
            <li class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8">
                <p class="text-sm font-semibold text-zinc-500">Langkah 1</p>
                <h3 class="mt-3 text-xl font-semibold tracking-tight text-zinc-950">Kunjungi katalog</h3>
                <p class="mt-3 leading-7 text-zinc-700">
                    Buka katalog untuk menjelajahi pilihan produk yang tersedia.
                </p>
            </li>
            <li class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8">
                <p class="text-sm font-semibold text-zinc-500">Langkah 2</p>
                <h3 class="mt-3 text-xl font-semibold tracking-tight text-zinc-950">Buka detail produk</h3>
                <p class="mt-3 leading-7 text-zinc-700">
                    Pilih produk untuk membaca informasi dan variasi yang tercantum pada halaman detailnya.
                </p>
            </li>
            <li class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8">
                <p class="text-sm font-semibold text-zinc-500">Langkah 3</p>
                <h3 class="mt-3 text-xl font-semibold tracking-tight text-zinc-950">Pelajari informasinya</h3>
                <p class="mt-3 leading-7 text-zinc-700">
                    Gunakan informasi katalog dan detail produk sebagai tahap awal sebelum proses pemesanan dilanjutkan.
                </p>
            </li>
        </ol>

        <a href="{{ route('catalog.index') }}"
           class="mt-8 inline-flex items-center justify-center rounded-lg bg-zinc-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900">
            Jelajahi Katalog
        </a>
    </div>
</section>
