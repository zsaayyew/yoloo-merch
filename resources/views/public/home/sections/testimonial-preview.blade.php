{{-- PAGE-CP-001: tidak ada ulasan contoh; preview menunggu record published ADM. --}}
@if (!empty($testimonialPreview) && $testimonialPreview->isNotEmpty())
    <section aria-labelledby="home-testimonials-title" class="bg-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-end justify-between gap-4 px-4 py-14 sm:px-6 lg:px-8"><div><p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#c75d1d]">Cerita pelanggan</p><h2 id="home-testimonials-title" class="mt-2 text-3xl font-black text-[#181818]">Ulasan</h2></div><a href="{{ route('reviews') }}#testimonials" class="font-bold text-[#124f53] underline decoration-[#58C0C8] decoration-2 underline-offset-4">Baca ulasan <span aria-hidden="true">→</span></a></div>
    </section>
@endif
