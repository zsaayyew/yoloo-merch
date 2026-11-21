{{-- CAT-FR-010 · Aksi pemesanan · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
<div class="flex flex-col gap-3 border-t border-[#dde4df] pt-5 sm:flex-row sm:flex-wrap">
    @if ($priceModel !== 'quotation' && $product->allows_direct_purchase && \Illuminate\Support\Facades\Route::has('cart.store'))
        @php
            $replaceItemId = request()->query('ubah');
            if ($replaceItemId === null && in_array(old('product_id'), [$product->id, (string) $product->id], true)) {
                $replaceItemId = old('replace_item_id');
            }
        @endphp
        @if (filter_var($replaceItemId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false)
            <input type="hidden" name="replace_item_id" value="{{ $replaceItemId }}">
        @endif
        <button type="submit" formaction="{{ route('cart.store') }}" data-catalog-cart
                class="min-h-12 rounded-full bg-[#5AC5CB] px-5 py-2.5 text-sm font-bold text-[#182F32] hover:bg-[#70d1d6] disabled:cursor-not-allowed disabled:opacity-55">
            Tambah ke keranjang
        </button>
    @endif

    @if (\Illuminate\Support\Facades\Route::has('custom-orders.create'))
        <button type="submit" name="intent" value="quotation" data-catalog-quotation
                class="min-h-12 rounded-full border border-[#a6501e] bg-[#fff5ec] px-5 py-2.5 text-sm font-bold text-[#743914] hover:bg-[#ffe8d6] disabled:cursor-not-allowed disabled:opacity-55">
            Minta penawaran
        </button>
    @endif

    @unless (\Illuminate\Support\Facades\Route::has('cart.store') || \Illuminate\Support\Facades\Route::has('custom-orders.create'))
        <p class="text-sm text-[#465b5d]">Pemesanan melalui website sedang tidak tersedia.</p>
    @endunless
</div>
