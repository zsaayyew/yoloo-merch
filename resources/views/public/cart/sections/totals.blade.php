{{--
    Section : Ringkasan total keranjang - PAGE-ORD-001
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Total estimasi hanya dari item yang tersedia. Bukan harga final;
              admin mengonfirmasi harga lewat WhatsApp. Checkout diblokir jika ada item tidak tersedia.
    Status  : diimplementasikan.
--}}
<aside class="rounded-xl border border-gray-200 bg-gray-50 p-5 lg:sticky lg:top-6">
    <h2 class="font-semibold text-gray-900">Ringkasan</h2>

    <dl class="mt-4 space-y-2 text-sm">
        <div class="flex justify-between">
            <dt class="text-gray-600">Jumlah produk</dt>
            <dd class="font-medium text-gray-900">{{ $rows->where('available', true)->count() }}</dd>
        </div>
        <div class="flex justify-between border-t border-gray-200 pt-3 text-base">
            <dt class="font-semibold text-gray-900">Total estimasi</dt>
            <dd class="font-bold text-gray-900">{{ \App\Support\Money::format($estimated_total) }}</dd>
        </div>
    </dl>

    <p class="mt-3 text-xs text-gray-500">
        Harga di atas adalah <strong>estimasi</strong>. Harga final dan pembayaran dikonfirmasi admin
        melalui WhatsApp setelah pesanan dikirim. Pesanan diambil langsung (pickup).
    </p>

    @if ($has_unavailable)
        <p class="mt-4 rounded-lg bg-amber-100 p-3 text-sm text-amber-900">
            Ada produk yang tidak tersedia. Hapus atau ubah dulu sebelum checkout.
        </p>
    @endif

    <div class="mt-5 space-y-3">
        @if (Route::has('checkout.customer') && ! $has_unavailable)
            <a href="{{ route('checkout.customer') }}"
                class="flex w-full items-center justify-center rounded-lg bg-gray-900 px-6 py-3 font-semibold text-white hover:bg-gray-700">
                Lanjut ke Checkout
            </a>
        @else
            <button type="button" disabled
                class="flex w-full cursor-not-allowed items-center justify-center rounded-lg bg-gray-400 px-6 py-3 font-semibold text-white">
                Lanjut ke Checkout
            </button>
        @endif

        <a href="{{ route('catalog.index') }}"
            class="flex w-full items-center justify-center rounded-lg border border-gray-300 px-6 py-3 font-semibold text-gray-900 hover:bg-white">
            Lanjut belanja
        </a>
    </div>
</aside>
