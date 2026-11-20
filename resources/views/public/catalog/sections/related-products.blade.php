{{-- PAGE-CAT-003 · Produk terkait · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
@if ($related->isNotEmpty())
    <section class="mt-12" aria-labelledby="catalog-related">
        <h2 id="catalog-related" class="mb-4 text-2xl font-bold">Produk terkait</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($related as $entry)
                <x-catalog.product-card :product="$entry" :image="$images->get($entry->id)?->first()" :pricing="$pricing->get($entry->id)" />
            @endforeach
        </div>
    </section>
@endif