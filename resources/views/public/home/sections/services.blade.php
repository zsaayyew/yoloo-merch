{{-- PAGE-CP-001: ringkasan layanan umum, detail produk tetap milik CAT. --}}
<section aria-labelledby="home-services-title" class="bg-[#f8f8f8]">
    <div class="mx-auto max-w-7xl px-4 {{ $showFeaturedProducts ? 'py-16 sm:py-20' : 'pb-16 pt-8 sm:pb-20 sm:pt-10' }} sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#14747b]">Apa yang kami kerjakan</p>
            <h2 id="home-services-title" class="mt-3 text-3xl font-black tracking-tight text-[#181818] sm:text-4xl">Dari merchandise custom hingga layanan printing.</h2>
        </div>
        <div class="mt-9 grid gap-5 md:grid-cols-2">
            <article class="relative overflow-hidden rounded-3xl border border-[#181818]/10 bg-white p-7 shadow-sm sm:p-9">
                <span aria-hidden="true" class="absolute right-5 top-3 text-5xl font-black text-[#E87830]/30">01</span>
                <span class="inline-grid h-12 w-12 place-items-center rounded-2xl bg-[#E87830]/20 text-2xl" aria-hidden="true">✦</span>
                <h3 class="mt-5 text-xl font-extrabold text-[#181818]">Custom merchandise</h3>
                <p class="mt-2 max-w-md leading-7 text-[#181818]/70">Jelajahi pilihan merchandise yang dapat disesuaikan melalui produk dan opsi yang tercantum di katalog.</p>
            </article>
            <article class="relative overflow-hidden rounded-3xl border border-[#181818]/10 bg-white p-7 shadow-sm sm:p-9">
                <span aria-hidden="true" class="absolute right-5 top-3 text-5xl font-black text-[#58C0C8]/50">02</span>
                <span class="inline-grid h-12 w-12 place-items-center rounded-2xl bg-[#58C0C8]/25 text-2xl" aria-hidden="true">▧</span>
                <h3 class="mt-5 text-xl font-extrabold text-[#181818]">Printing services</h3>
                <p class="mt-2 max-w-md leading-7 text-[#181818]/70">Temukan layanan printing yang tersedia beserta informasi produk pada katalog YOLOO Merch.</p>
            </article>
        </div>
        @if (\Illuminate\Support\Facades\Route::has('catalog.index'))
            <a href="{{ route('catalog.index') }}" class="mt-7 inline-flex items-center gap-2 rounded-full border-2 border-[#181818] px-5 py-2.5 text-sm font-bold text-[#181818] transition hover:bg-[#58C0C8]/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">Lihat produk di katalog <span aria-hidden="true">→</span></a>
        @endif
    </div>
</section>
