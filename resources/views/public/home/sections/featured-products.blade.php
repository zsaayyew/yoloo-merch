{{-- PAGE-CP-001: ringkasan katalog dirender jika CAT memberikan data terkonfirmasi. --}}
@if ($showFeaturedProducts)
    <section aria-labelledby="home-featured-title" class="bg-[#f8f8f8]">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div><p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#14747b]">Dari katalog</p><h2 id="home-featured-title" class="mt-2 text-3xl font-black tracking-tight text-[#181818] sm:text-4xl">Produk yang tersedia</h2></div>
                <a href="{{ route('catalog.index') }}" class="font-bold text-[#124f53] underline decoration-[#58C0C8] decoration-2 underline-offset-4">Lihat semua produk <span aria-hidden="true">→</span></a>
            </div>
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($featuredProducts as $product)
                    <x-catalog.product-card :product="$product" :image="$featuredImages[$product->id] ?? null" :pricing="$featuredPricing[$product->id] ?? null" />
                @endforeach
            </div>
        </div>
    </section>
@else
    <section aria-labelledby="home-featured-title" class="bg-[#f8f8f8]">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 px-4 py-8 sm:px-6 sm:py-10 md:flex-row md:items-center md:justify-between lg:px-8">
            <div><p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#14747b]">Dari katalog</p><h2 id="home-featured-title" class="mt-2 text-2xl font-black text-[#181818]">Temukan pilihan di katalog</h2><p class="mt-2 max-w-2xl leading-7 text-[#181818]/70">Produk yang tersedia akan ditampilkan di sini.</p></div>
            @if (\Illuminate\Support\Facades\Route::has('catalog.index'))
                <a href="{{ route('catalog.index') }}" class="inline-flex shrink-0 items-center justify-center rounded-full bg-[#14747b] px-5 py-3 text-sm font-bold text-white hover:bg-[#105d62] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#181818]">Lihat Katalog</a>
            @endif
        </div>
    </section>
@endif
