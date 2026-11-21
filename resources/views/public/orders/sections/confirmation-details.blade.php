{{--
    Section : Rincian pesanan terkirim - PAGE-ORD-004
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Kode WEB, status awal, kontak, dan item snapshot. Angka berlabel estimasi.
    Status  : diimplementasikan.
--}}
<div class="mt-8 rounded-xl border border-gray-200 p-5">
    <div class="text-center">
        <p class="text-sm text-gray-500">Kode pesanan</p>
        <p class="mt-1 font-mono text-2xl font-bold tracking-wider text-gray-900">{{ $order->code }}</p>
        <p class="mt-1 text-sm text-gray-600">
            Status: <span class="font-medium text-gray-900">{{ $order->status->label() }}</span>
            &middot; Dikirim {{ $order->ordered_at->format('d/m/Y H:i') }}
        </p>
    </div>

    <dl class="mt-5 grid gap-3 border-t border-gray-100 pt-4 text-sm sm:grid-cols-2">
        <div>
            <dt class="text-gray-500">Nama</dt>
            <dd class="font-medium text-gray-900">{{ $order->customer->name }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">WhatsApp</dt>
            <dd class="font-medium text-gray-900">+{{ $order->customer->whatsapp_number }}</dd>
        </div>
    </dl>
</div>

<x-ordering.order-summary
    class="mt-6"
    title="Produk dipesan"
    :total="$order->estimated_total"
    :items="$order->items->map(fn ($item) => [
        'name' => $item->product_name_snapshot,
        'options' => $item->options_snapshot,
        'quantity' => $item->quantity,
        'unit_price' => $item->unit_price,
        'subtotal' => $item->subtotal,
    ])" />
