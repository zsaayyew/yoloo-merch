{{-- PAGE-CP-001: preview hanya ditampilkan dari foto published milik ADM. --}}
@if (!empty($galleryPreview) && $galleryPreview->isNotEmpty())
    <section aria-labelledby="home-gallery-title" class="bg-[#f8f8f8]">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#14747b]">Hasil karya</p><h2 id="home-gallery-title" class="mt-2 text-3xl font-black text-[#181818]">Sekilas galeri</h2></div><a href="{{ route('gallery') }}" class="font-bold text-[#124f53] underline decoration-[#58C0C8] decoration-2 underline-offset-4">Buka galeri <span aria-hidden="true">→</span></a></div>
        </div>
    </section>
@endif
