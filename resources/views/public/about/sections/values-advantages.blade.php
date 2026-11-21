{{-- PAGE-CP-002: hanya menampilkan nilai/keunggulan yang disahkan dan dipublikasikan ADM. --}}
@if (!empty($aboutContent['values']) || !empty($aboutContent['strengths']))
    <section aria-labelledby="about-values-title" class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#c75d1d]">Yang kami pegang</p><h2 id="about-values-title" class="mt-3 text-3xl font-black text-[#181818] sm:text-4xl">Nilai &amp; keunggulan</h2>
            @if (!empty($aboutContent['values']))<div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">@foreach ((array) $aboutContent['values'] as $value)<article class="rounded-2xl border border-[#181818]/10 bg-[#f8f8f8] p-6"><p class="leading-7 text-[#181818]/75">{{ $value }}</p></article>@endforeach</div>@endif
            @if (!empty($aboutContent['strengths']))<div class="mt-8 rounded-3xl bg-[#E8C050]/40 p-6 sm:p-8"><h3 class="text-xl font-extrabold text-[#181818]">Keunggulan</h3><div class="mt-3 whitespace-pre-line leading-7 text-[#181818]/80">{{ is_array($aboutContent['strengths']) ? implode("\n", $aboutContent['strengths']) : $aboutContent['strengths'] }}</div></div>@endif
        </div>
    </section>
@endif
