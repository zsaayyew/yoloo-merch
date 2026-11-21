{{--
    Component : <x-ordering.order-summary :items="..." :total="..." />
    Domain    : PRD-03 ORD (Raisyah)
    Catatan   : Daftar item + total estimasi. Dipakai di checkout, ringkasan, dan konfirmasi.
                $items: iterable berisi array [name, options, quantity, unit_price, subtotal].
                Semua angka berlabel Estimasi. Tidak menampilkan QRIS/instruksi pembayaran.
    Status    : diimplementasikan.
--}}
@props(['items', 'total', 'title' => 'Pesanan'])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 bg-gray-50 p-5']) }}>
    <h2 class="font-semibold text-gray-900">{{ $title }}</h2>

    <ul class="mt-4 divide-y divide-gray-200">
        @foreach ($items as $line)
            <li class="flex justify-between gap-4 py-3 text-sm">
                <div class="min-w-0">
                    <p class="font-medium text-gray-900">{{ $line['name'] }}</p>
                    @foreach ($line['options'] ?? [] as $option)
                        <p class="text-gray-600">{{ $option['group'] }}: {{ $option['value'] }}</p>
                    @endforeach
                    <p class="text-gray-600">
                        {{ number_format($line['quantity'], 0, ',', '.') }} pcs
                        &times; estimasi {{ \App\Support\Money::format((int) $line['unit_price']) }}
                    </p>
                </div>
                <p class="shrink-0 font-semibold text-gray-900">{{ \App\Support\Money::format((int) $line['subtotal']) }}</p>
            </li>
        @endforeach
    </ul>

    <div class="mt-2 flex justify-between border-t border-gray-300 pt-3">
        <span class="font-semibold text-gray-900">Total estimasi</span>
        <span class="text-lg font-bold text-gray-900">{{ \App\Support\Money::format((int) $total) }}</span>
    </div>
    <p class="mt-2 text-xs text-gray-500">
        Harga di atas adalah <strong>estimasi</strong>. Harga final dikonfirmasi admin melalui WhatsApp.
    </p>
</div>
