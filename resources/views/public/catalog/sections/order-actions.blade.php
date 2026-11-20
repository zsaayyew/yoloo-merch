{{-- CAT-FR-010 · Aksi pemesanan · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
<div class="flex flex-wrap items-center gap-3">
    @if ($priceModel !== 'quotation' && $product->allows_direct_purchase && \Illuminate\Support\Facades\Route::has('cart.store'))
        <button type="submit" formaction="{{ route('cart.store') }}" data-catalog-cart
                class="rounded-lg bg-zinc-950 px-4 py-2 font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50">
            Tambah ke keranjang
        </button>
    @endif

    @if (\Illuminate\Support\Facades\Route::has('custom-orders.create'))
        <button type="submit" name="intent" value="quotation" data-catalog-quotation
                class="rounded-lg border border-zinc-400 bg-white px-4 py-2 font-semibold disabled:cursor-not-allowed disabled:opacity-50">
            Minta penawaran
        </button>
    @endif

    @unless (\Illuminate\Support\Facades\Route::has('cart.store') || \Illuminate\Support\Facades\Route::has('custom-orders.create'))
        <p class="text-sm text-zinc-700">Pemesanan melalui website sedang tidak tersedia.</p>
    @endunless
</div>