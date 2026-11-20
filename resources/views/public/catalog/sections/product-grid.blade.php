{{-- CAT-FR-002 · Daftar produk · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
<section aria-label="Hasil katalog">
    <p class="mb-4 text-sm text-zinc-700">{{ $products->total() }} produk</p>

    @if ($products->isEmpty())
        <div class="rounded-xl border border-zinc-200 p-6">
            <p class="font-semibold">Tidak ada produk pada halaman ini.</p>
            <p class="mt-1 text-zinc-700">Ubah kata kunci atau lihat semua kategori.</p>
            <a class="mt-3 inline-block underline" href="{{ route('catalog.index') }}">Lihat semua produk</a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($products as $entry)
                <x-catalog.product-card :product="$entry" :image="$images->get($entry->id)?->first()" :pricing="$pricing->get($entry->id)" />
            @endforeach
        </div>
    @endif

    @if ($products->hasPages())
        <div class="mt-6">{{ $products->links() }}</div>
    @endif
</section>