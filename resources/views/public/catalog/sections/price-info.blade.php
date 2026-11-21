{{-- CAT-FR-007 · Informasi harga · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
<div class="rounded-r-2xl border-l-4 border-[#5AC5CB] bg-[#e6f4f3] px-5 py-4">
    <x-catalog.price-display :product="$product" :pricing="$pricing->get($product->id)" />
</div>
