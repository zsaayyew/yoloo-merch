{{-- PAGE-CP-002: disembunyikan sampai ADM menyediakan visi dan misi yang disahkan. --}}
@if (!empty($aboutContent['vision']) || !empty($aboutContent['mission']))
    <section aria-labelledby="about-vision-title" class="bg-[#f8f8f8]">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#14747b]">Arah kami</p><h2 id="about-vision-title" class="mt-3 text-3xl font-black text-[#181818] sm:text-4xl">Visi &amp; Misi</h2>
            <div class="mt-8 grid gap-5 md:grid-cols-2">
                @if (!empty($aboutContent['vision']))<article class="rounded-3xl border border-[#181818]/10 bg-white p-7"><h3 class="text-xl font-extrabold text-[#181818]">Visi</h3><p class="mt-3 leading-7 text-[#181818]/75">{{ $aboutContent['vision'] }}</p></article>@endif
                @if (!empty($aboutContent['mission']))<article class="rounded-3xl border border-[#181818]/10 bg-white p-7"><h3 class="text-xl font-extrabold text-[#181818]">Misi</h3><p class="mt-3 whitespace-pre-line leading-7 text-[#181818]/75">{{ $aboutContent['mission'] }}</p></article>@endif
            </div>
        </div>
    </section>
@endif
