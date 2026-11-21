<section aria-labelledby="catalog-results-title" class="mt-8 sm:mt-10">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 id="catalog-results-title" class="flex items-center gap-3 text-2xl font-extrabold tracking-tight sm:text-3xl">
            <span aria-hidden="true" class="h-6 w-1.5 rounded-full bg-[#5AC5CB]"></span>
            {{ $filters !== [] ? 'Hasil pencarian' : 'Daftar produk' }}
        </h2>
        <p class="rounded-full border border-[#CCD5D0] bg-white px-3 py-1 text-sm font-semibold tabular-nums">{{ $products->total() }} produk</p>
    </div>

    @if ($products->isEmpty())
        <div class="rounded-2xl border border-dashed border-[#B7C6C1] bg-white p-7 sm:p-10">
            <p class="text-lg font-bold">Tidak ada produk pada halaman ini.</p>
            <p class="mt-2 text-sm text-[#465C5D]">Ubah kata kunci atau lihat semua kategori.</p>
            <a class="mt-5 inline-flex min-h-11 items-center rounded-lg bg-[#DDF3F0] px-4 text-sm font-bold hover:bg-[#5AC5CB] focus-visible:outline-2 focus-visible:outline-offset-3"
               href="{{ route('catalog.index') }}">Lihat semua produk</a>
        </div>
    @else
        <div class="grid min-w-0 grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($products as $entry)
                <x-catalog.product-card variant="catalog" :product="$entry" :image="$images->get($entry->id)?->first()" :pricing="$pricing->get($entry->id)" />
            @endforeach
        </div>
    @endif

    @if ($products->hasPages())
        <nav aria-label="Halaman hasil katalog" class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-[#D1D9D3] pt-5 text-sm">
            <span class="text-[#43595A]">Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}</span>
            <div class="flex flex-wrap items-center gap-2">
                @if ($products->previousPageUrl())
                    <a rel="prev" class="inline-flex min-h-11 items-center rounded-lg border border-[#B5C5C1] bg-white px-4 font-semibold hover:bg-[#E4F5F3] focus-visible:outline-2 focus-visible:outline-offset-3"
                       href="{{ $products->previousPageUrl() }}">Sebelumnya</a>
                @endif
                <div class="hidden items-center gap-1 sm:flex">
                    @foreach (array_filter(\Illuminate\Pagination\UrlWindow::make($products->onEachSide(1))) as $pageLinks)
                        @unless ($loop->first)<span aria-hidden="true" class="px-1">…</span>@endunless
                        @foreach ($pageLinks as $page => $url)
                            @if ($page === $products->currentPage())
                                <span aria-current="page" aria-label="Halaman {{ $page }}" class="grid size-10 place-items-center rounded-lg bg-[#182F32] font-bold text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" aria-label="Halaman {{ $page }}" class="grid size-10 place-items-center rounded-lg font-semibold hover:bg-[#DDF3F0] focus-visible:outline-2 focus-visible:outline-offset-2">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endforeach
                </div>
                @if ($products->nextPageUrl())
                    <a rel="next" class="inline-flex min-h-11 items-center rounded-lg bg-[#5AC5CB] px-4 font-bold hover:bg-[#82D4D7] focus-visible:outline-2 focus-visible:outline-offset-3"
                       href="{{ $products->nextPageUrl() }}">Berikutnya</a>
                @endif
            </div>
        </nav>
    @endif
</section>
