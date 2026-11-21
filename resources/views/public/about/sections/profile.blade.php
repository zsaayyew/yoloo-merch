{{--
    PAGE-CP-002 - Section 'profile' (Tentang Kami)
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : Profil usaha berdasarkan informasi yang disetujui dalam Master PRD.
    Status  : diimplementasikan.
--}}

<section aria-labelledby="about-profile-title" class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 sm:py-20 md:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] md:items-start lg:px-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-zinc-500">
                Profil Usaha
            </p>
            <h1 id="about-profile-title" class="mt-3 text-3xl font-black tracking-tight text-[#181818] sm:text-4xl">
                Tentang YOLOO Merch
            </h1>
        </div>

        <div class="max-w-2xl text-base leading-7 text-[#181818]/75 sm:text-lg sm:leading-8">
            <p class="mb-3 font-bold text-[#14747b]">Merchandise custom dan layanan printing.</p>
            <p>
                YOLOO Merch menyediakan jasa pembuatan merchandise custom serta layanan cetak berdasarkan produk yang tersedia dalam katalog.
            </p>
            <p class="mt-4">
                Layanan ini ditujukan bagi individu, mahasiswa, organisasi, komunitas, dan pihak lain yang mencari merchandise. Pilihan produk beserta variasinya dapat dilihat melalui katalog.
            </p>
            <a href="{{ route('catalog.index') }}"
               class="mt-6 inline-flex items-center font-bold text-[#124f53] underline decoration-[#58C0C8] decoration-2 underline-offset-4 hover:decoration-[#181818] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#14747b]">
                Jelajahi Katalog
                <span class="sr-only"> YOLOO Merch</span>
            </a>
        </div>
    </div>
</section>
