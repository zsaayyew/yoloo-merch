{{--
    PAGE-FIN-002 - Section 'items' (Detail Order / Request)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Order menampilkan item snapshot; request menampilkan kebutuhan pelanggan.
    Status  : diimplementasikan.
--}}
@use('App\Support\Money')

<section class="rounded-xl border border-gray-200 bg-white p-4">
    @if ($order)
        <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Item Pesanan</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="text-left text-xs font-semibold uppercase text-gray-500">
                    <tr>
                        <th class="py-2 pr-4">Produk</th>
                        <th class="py-2 pr-4">Pilihan</th>
                        <th class="py-2 pr-4 text-right">Jumlah</th>
                        <th class="py-2 pr-4 text-right">Harga satuan</th>
                        <th class="py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($order->items as $item)
                        <tr class="align-top">
                            <td class="py-3 pr-4">
                                <div class="font-medium text-gray-900">{{ $item->product_name_snapshot }}</div>
                                @if ($item->description)
                                    <div class="mt-1 text-xs text-gray-500">{{ $item->description }}</div>
                                @endif
                            </td>
                            <td class="py-3 pr-4">
                                @include('admin.orders.sections.snapshot-list', ['data' => $item->options_snapshot, 'money' => false])
                            </td>
                            <td class="py-3 pr-4 text-right">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap py-3 pr-4 text-right">{{ Money::format($item->unit_price) }}</td>
                            <td class="whitespace-nowrap py-3 text-right font-medium text-gray-900">
                                {{ Money::format($item->subtotal) }}
                                @if ($item->price_status === \App\Models\OrderItem::PRICE_ESTIMATE)
                                    <div class="text-xs font-normal text-orange-600">Estimasi</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-gray-500">Belum ada item.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Kebutuhan Pelanggan</h2>

        <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-gray-500">Produk acuan</dt>
                <dd class="font-medium text-gray-900">{{ $orderRequest->product_name_snapshot ?? 'Tanpa produk acuan' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Jumlah</dt>
                <dd class="font-medium text-gray-900">{{ number_format($orderRequest->quantity, 0, ',', '.') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Deadline diinginkan</dt>
                <dd class="font-medium text-gray-900">{{ $orderRequest->desired_deadline?->format('d/m/Y') ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Budget pelanggan</dt>
                <dd class="font-medium text-gray-900">{{ $orderRequest->budget !== null ? Money::format($orderRequest->budget) : '-' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-gray-500">Deskripsi</dt>
                <dd class="whitespace-pre-line text-gray-900">{{ $orderRequest->description }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-gray-500">Spesifikasi</dt>
                <dd class="whitespace-pre-line text-gray-900">{{ $orderRequest->specification ?? '-' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-gray-500">Catatan pelanggan</dt>
                <dd class="whitespace-pre-line text-gray-900">{{ $orderRequest->notes ?? '-' }}</dd>
            </div>
        </dl>

        @if (! empty($orderRequest->options_snapshot))
            <div class="mt-4 border-t border-gray-100 pt-4">
                <h3 class="mb-2 text-sm font-medium text-gray-700">Pilihan produk saat pengajuan</h3>
                @include('admin.orders.sections.snapshot-list', ['data' => $orderRequest->options_snapshot, 'money' => false])
            </div>
        @endif

        @if (! empty($orderRequest->estimate_snapshot))
            <div class="mt-4 border-t border-gray-100 pt-4">
                <h3 class="mb-2 text-sm font-medium text-gray-700">Estimasi saat pengajuan <span class="font-normal text-gray-500">(informasi, bukan harga)</span></h3>
                @include('admin.orders.sections.snapshot-list', ['data' => $orderRequest->estimate_snapshot, 'money' => true])
            </div>
        @endif
    @endif
</section>
