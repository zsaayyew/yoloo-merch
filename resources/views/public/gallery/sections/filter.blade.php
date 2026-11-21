{{-- PAGE-CP-004: kategori selalu berasal dari daftar aktif ADM; tidak ada kategori hardcoded. --}}
<section aria-labelledby="gallery-title" class="bg-[#181818] text-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-16 lg:px-8">
        <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#8ce0e4]">Dari ide menjadi karya</p>
        <h1 id="gallery-title" class="mt-3 text-4xl font-black tracking-tight sm:text-5xl">Galeri YOLOO Merch</h1>
        <p class="mt-4 max-w-2xl leading-7 text-white/75">Lihat hasil produksi yang telah dipublikasikan.</p>
        @if ($galleryCategories->isNotEmpty() && $galleryItems->isNotEmpty())
            <div class="mt-8 flex flex-wrap gap-2" role="group" aria-label="Filter kategori galeri">
                <button type="button" data-gallery-filter="all" aria-pressed="true" class="rounded-full border border-transparent px-4 py-2 text-sm font-bold text-white aria-pressed:bg-[#58C0C8] aria-pressed:text-[#181818] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Semua</button>
                @foreach ($galleryCategories as $category)
                    <button type="button" data-gallery-filter="{{ $category['slug'] }}" aria-pressed="false" class="rounded-full border border-white/30 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10 aria-pressed:border-transparent aria-pressed:bg-[#58C0C8] aria-pressed:text-[#181818] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">{{ $category['name'] }}</button>
                @endforeach
            </div>
            <p class="sr-only" data-gallery-filter-status aria-live="polite"></p>
        @endif
    </div>
</section>
