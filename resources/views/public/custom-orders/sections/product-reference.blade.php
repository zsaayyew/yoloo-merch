{{--
    Section : Referensi produk (opsional) - PAGE-ORD-005
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Muncul jika datang dari tombol "Minta penawaran" di katalog
              (?produk={slug}&opsi[]={id}&jumlah={qty}). Opsi divalidasi ulang di server saat kirim.
    Status  : diimplementasikan.
--}}
@if ($product)
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
        <p class="text-sm text-gray-500">Referensi produk</p>
        <p class="mt-1 font-semibold text-gray-900">{{ $product->name }}</p>
        @php
            // Setelah gagal validasi, pakai opsi yang tadi terkirim; jika tidak, opsi dari halaman produk.
            $selectedOptions = old('option_value_ids') !== null
                ? collect($referenceOptions)->whereIn('option_value_id', array_map('intval', (array) old('option_value_ids')))->values()->all()
                : $referenceOptions;
        @endphp
        @if (! empty($selectedOptions))
            <ul class="mt-1 space-y-0.5 text-sm text-gray-700">
                @foreach ($selectedOptions as $option)
                    <li>
                        {{ $option['group'] }}: <span class="font-medium text-gray-900">{{ $option['value'] }}</span>
                        <input type="hidden" name="option_value_ids[]" value="{{ $option['option_value_id'] }}">
                    </li>
                @endforeach
            </ul>
        @endif
        <p class="mt-1 text-sm text-gray-600">
            Kamu boleh menjelaskan perubahan atau kebutuhan khusus berdasarkan produk ini.
        </p>
        <input type="hidden" name="product_slug" value="{{ $product->slug }}">
    </div>
@endif
