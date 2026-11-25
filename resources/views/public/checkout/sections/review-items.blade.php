{{--
    Section : Daftar item untuk ditinjau - PAGE-ORD-003
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Item, opsi, jumlah, estimasi. Item dengan harga berubah diberi penanda (ORD-FR-008 alur alternatif).
    Status  : diimplementasikan.
--}}
<div>
    <x-ordering.order-summary
        title="Produk"
        :total="$estimated_total"
        :items="$rows->map(fn ($row) => [
            'name' => $row['item']->product_name_snapshot,
            'options' => $row['item']->options_snapshot,
            'quantity' => $row['item']->quantity,
            'unit_price' => $row['unit_price'],
            'subtotal' => $row['subtotal'],
            'files' => isset($itemAttachments) ? $itemAttachments->get($row['item']->id, collect())->pluck('original_name')->all() : [],
        ])" />

    @if ($has_price_changes)
        <p class="mt-2 text-sm text-amber-700">Sebagian estimasi harga diperbarui mengikuti tarif terbaru.</p>
    @endif

    <a href="{{ route('cart.index') }}" class="mt-3 inline-block text-sm font-medium text-gray-700 underline hover:text-gray-900">Ubah keranjang</a>
</div>
