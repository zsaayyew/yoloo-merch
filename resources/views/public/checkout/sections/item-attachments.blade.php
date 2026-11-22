{{--
    Section : Lampiran desain per item - PAGE-ORD-002
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : ORD-FR-011 (lampiran tertaut ke item). Opsional. Setiap item keranjang punya kotak upload
              sendiri; file ikut tersimpan ke item order saat pesanan dikirim.
    Status  : diimplementasikan.
--}}
<fieldset class="space-y-4">
    <legend class="text-lg font-semibold text-gray-900">File desain (opsional)</legend>
    <p class="text-sm text-gray-600">
        Lampirkan desain atau logo untuk produk yang membutuhkannya. Admin akan mengonfirmasi detailnya lewat WhatsApp.
    </p>

    @foreach ($rows as $row)
        @php($cartItem = $row['item'])
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm font-semibold text-gray-900">{{ $cartItem->product_name_snapshot }}</p>
            @if (! empty($cartItem->options_snapshot))
                <p class="mb-3 text-xs text-gray-500">
                    {{ collect($cartItem->options_snapshot)->map(fn ($o) => $o['group'] . ': ' . $o['value'])->join(' · ') }}
                </p>
            @endif

            <x-form.file-upload
                :attachments="$itemAttachments->get($cartItem->id, collect())"
                :cart-item-id="$cartItem->id"
                input-id="design_file_{{ $cartItem->id }}"
                compact />
        </div>
    @endforeach
</fieldset>
