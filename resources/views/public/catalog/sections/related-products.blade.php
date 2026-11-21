{{-- PAGE-CAT-003 · Produk terkait · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
@if ($related->isNotEmpty())
    <section class="mt-14 border-t border-[#ccd5d1] pt-8 sm:mt-20 sm:pt-10" aria-labelledby="catalog-related">
        <h2 id="catalog-related" class="mb-6 text-3xl font-black tracking-tight">Produk terkait</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($related as $entry)
                <x-catalog.product-card variant="catalog" :product="$entry" :image="$images->get($entry->id)?->first()" :pricing="$pricing->get($entry->id)" />
            @endforeach
        </div>
    </section>
@endif
