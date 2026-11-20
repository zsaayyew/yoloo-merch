{{-- CAT-FR-007 · Informasi harga · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
<div class="rounded-xl border border-zinc-200 px-4 py-3">
    <x-catalog.price-display :product="$product" :pricing="$pricing->get($product->id)" />
</div>