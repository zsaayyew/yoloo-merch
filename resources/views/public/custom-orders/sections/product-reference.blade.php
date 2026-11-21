{{--
    Section : Referensi produk (opsional) - PAGE-ORD-005
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Muncul jika datang dari tombol "Ajukan Custom" di katalog (?produk={slug}).
    Status  : diimplementasikan.
--}}
@if ($product)
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
        <p class="text-sm text-gray-500">Referensi produk</p>
        <p class="mt-1 font-semibold text-gray-900">{{ $product->name }}</p>
        <p class="mt-1 text-sm text-gray-600">
            Kamu boleh menjelaskan perubahan atau kebutuhan khusus berdasarkan produk ini.
        </p>
        <input type="hidden" name="product_slug" value="{{ $product->slug }}">
    </div>
@endif
