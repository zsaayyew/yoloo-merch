{{--
    Section : Rekap keranjang - PAGE-ORD-002
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Ringkasan item dan total estimasi (dihitung ulang server).
    Status  : diimplementasikan.
--}}
<x-ordering.order-summary
    title="Keranjang"
    :total="$estimated_total"
    :items="$rows->map(fn ($row) => [
        'name' => $row['item']->product_name_snapshot,
        'options' => $row['item']->options_snapshot,
        'quantity' => $row['item']->quantity,
        'unit_price' => $row['unit_price'],
        'subtotal' => $row['subtotal'],
    ])"
    class="lg:sticky lg:top-6" />
