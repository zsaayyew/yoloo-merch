{{-- PAGE-CP-003: portofolio menggunakan data publik ADM; detail panjang tidak diwajibkan. --}}
<section id="collaborations" aria-labelledby="reviews-collaborations-title" class="bg-[#f8f8f8]">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <div class="max-w-2xl"><p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#c75d1d]">Berkarya bersama</p><h2 id="reviews-collaborations-title" class="mt-3 text-3xl font-black tracking-tight text-[#181818] sm:text-4xl">Kolaborasi</h2><p class="mt-3 leading-7 text-[#181818]/70">Bagian ini menampilkan organisasi dan hasil merchandise kolaborasi yang telah disetujui.</p></div>
        @if ($collaborations->isEmpty())
            <p class="mt-8 rounded-2xl border border-dashed border-[#181818]/20 bg-white p-6 text-[#181818]/70">Belum ada kolaborasi yang dipublikasikan.</p>
        @else
            <div class="mt-8 grid gap-5 md:grid-cols-2">
                @foreach ($collaborations as $collaboration)
                    <article class="overflow-hidden rounded-3xl border border-[#181818]/10 bg-white shadow-sm">
                        @if (!empty($collaboration['result_images']))<div class="grid grid-cols-2 gap-1">@foreach (array_slice($collaboration['result_images'], 0, 2) as $image)<img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? '' }}" loading="lazy" class="aspect-[4/3] w-full object-cover">@endforeach</div>@endif
                        <div class="p-6 sm:p-7">
                            @if (!empty($collaboration['logo_url']))<img src="{{ $collaboration['logo_url'] }}" alt="Logo {{ $collaboration['organization_name'] }}" loading="lazy" class="mb-4 h-12 max-w-40 object-contain object-left">@endif
                            <h3 class="text-xl font-extrabold text-[#181818]">{{ $collaboration['organization_name'] }}</h3>
                            <p class="mt-2 text-sm font-semibold text-[#14747b]">{{ $collaboration['merchandise_type'] }}</p>
                            @if (!empty($collaboration['short_description']))<p class="mt-3 leading-7 text-[#181818]/70">{{ $collaboration['short_description'] }}</p>@endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
        <div class="mt-9 flex flex-wrap gap-3"><a href="{{ route('catalog.index') }}" class="inline-flex rounded-full bg-[#14747b] px-5 py-3 text-sm font-bold text-white hover:bg-[#105d62]">Jelajahi katalog <span aria-hidden="true" class="ml-2">→</span></a><a href="{{ route('contact') }}" class="inline-flex rounded-full border-2 border-[#181818] px-5 py-3 text-sm font-bold text-[#181818] hover:bg-white">Kontak</a></div>
    </div>
</section>
