{{--
    Section : Daftar item keranjang - PAGE-ORD-001
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Ubah jumlah, ubah opsi, dan hapus item dengan konfirmasi (ORD-FR-002).
              Item tidak tersedia ditandai dan tidak dihitung dalam total (ORD-FR-004).
              "Ubah opsi" membuka detail produk CAT dengan query ?ubah={id}&opsi[]=..&jumlah=..
    Status  : diimplementasikan.
--}}
<ul class="space-y-4">
    @foreach ($rows as $row)
        @php($item = $row['item'])
        <li @class([
            'rounded-xl border p-4 sm:p-5',
            'border-gray-200' => $row['available'],
            'border-amber-300 bg-amber-50' => ! $row['available'],
        ])>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <p class="font-semibold text-gray-900">{{ $item->product_name_snapshot }}</p>

                    @if (! empty($item->options_snapshot))
                        <ul class="mt-1 space-y-0.5 text-sm text-gray-600">
                            @foreach ($item->options_snapshot as $option)
                                <li>{{ $option['group'] }}: <span class="text-gray-900">{{ $option['value'] }}</span></li>
                            @endforeach
                        </ul>
                    @endif

                    @if (! $row['available'])
                        <p class="mt-2 text-sm font-medium text-amber-800">{{ $row['message'] }}</p>
                        <p class="text-sm text-amber-800">Hapus item ini atau ubah jumlahnya untuk melanjutkan.</p>
                    @elseif ($row['price_changed'])
                        <p class="mt-2 text-sm text-amber-700">
                            Harga diperbarui mengikuti tarif terbaru:
                            <span class="line-through">{{ \App\Support\Money::format((int) $row['previous_subtotal']) }}</span>
                            &rarr; {{ \App\Support\Money::format($row['subtotal']) }}
                        </p>
                    @endif
                </div>

                <div class="shrink-0 text-left sm:text-right">
                    @if ($row['available'])
                        <p class="text-xs text-gray-500">
                            Estimasi {{ \App\Support\Money::format((int) $row['unit_price']) }} / pcs
                        </p>
                        <p class="text-lg font-bold text-gray-900">{{ \App\Support\Money::format($row['subtotal']) }}</p>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Estimasi</p>
                    @else
                        <p class="text-sm font-medium text-amber-800">Tidak dihitung</p>
                    @endif
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-gray-100 pt-4">
                <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2" data-submit-guard>
                    @csrf
                    @method('PATCH')
                    <label for="quantity-{{ $item->id }}" class="text-sm text-gray-600">Jumlah</label>
                    <input type="number" id="quantity-{{ $item->id }}" name="quantity" min="1" step="1" required
                        value="{{ $item->quantity }}"
                        class="w-24 rounded-lg border border-gray-300 px-3 py-1.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    <button type="submit" class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-900 hover:bg-gray-50">
                        Ubah
                    </button>
                </form>

                @if ($item->product && ! empty($item->options_snapshot))
                    <a href="{{ route('products.show', ['product' => $item->product->slug, 'ubah' => $item->id, 'opsi' => $item->option_value_ids, 'jumlah' => $item->quantity]) }}"
                        class="text-sm font-medium text-gray-700 underline hover:text-gray-900">
                        Ubah opsi
                    </a>
                @endif

                {{-- Tanpa data-submit-guard: jika pelanggan menekan Batal, tombol tidak boleh ikut terkunci. --}}
                <form method="POST" action="{{ route('cart.destroy', $item) }}" class="ml-auto"
                    onsubmit="return confirm('Hapus produk ini dari keranjang?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Hapus</button>
                </form>
            </div>
        </li>
    @endforeach
</ul>
