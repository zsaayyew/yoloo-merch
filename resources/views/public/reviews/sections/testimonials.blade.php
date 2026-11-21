{{-- PAGE-CP-003: daftar hanya menerima record approved dari ADM; tidak ada form ulasan publik. --}}
<section id="testimonials" aria-labelledby="reviews-testimonials-title" class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <div class="max-w-2xl"><p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#14747b]">Cerita pelanggan</p><h2 id="reviews-testimonials-title" class="mt-3 text-3xl font-black tracking-tight text-[#181818] sm:text-4xl">Ulasan pelanggan</h2><p class="mt-3 leading-7 text-[#181818]/70">Ulasan ditampilkan setelah dikurasi dan dipublikasikan.</p></div>
        @if ($testimonials->isEmpty())
            <p class="mt-8 rounded-2xl border border-dashed border-[#181818]/20 bg-[#f8f8f8] p-6 text-[#181818]/70">Belum ada ulasan yang dipublikasikan.</p>
        @else
            <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    <article class="rounded-3xl border border-[#181818]/10 bg-[#f8f8f8] p-6">
                        @if (!empty($testimonial['rating']))<p class="font-bold text-[#181818]" aria-label="Rating {{ $testimonial['rating'] }} dari 5">{{ str_repeat('★', max(0, min(5, (int) $testimonial['rating']))) }}</p>@endif
                        <blockquote class="mt-3 leading-7 text-[#181818]/80">“{{ $testimonial['review_text'] }}”</blockquote>
                        <p class="mt-5 font-extrabold text-[#181818]">{{ $testimonial['display_name'] }}</p>
                        @if (!empty($testimonial['ordered_product']))<p class="mt-1 text-sm text-[#181818]/60">{{ $testimonial['ordered_product'] }}</p>@endif
                        @if (!empty($testimonial['image_url']))<img class="mt-4 h-40 w-full rounded-2xl object-cover" src="{{ $testimonial['image_url'] }}" alt="{{ $testimonial['image_alt'] ?? '' }}" loading="lazy">@endif
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
