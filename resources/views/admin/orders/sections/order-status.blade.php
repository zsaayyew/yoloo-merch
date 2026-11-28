{{--
    PAGE-FIN-002 - Section 'order-status' (Detail Order)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Variabel dari OrderController::show: $order, $statusTargets, $canCancel.
              Status pembayaran dihitung otomatis dan tidak mengubah status pengerjaan (FIN-BR-006).
              Aturan transisi ada di StatusTransitionService.
    Status  : diimplementasikan (FIN-FR-010, FIN-FR-016).
--}}
<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="text-base font-semibold text-gray-900">Status Pengerjaan</h2>
    <p class="mt-1 text-sm text-gray-600">
        Status saat ini: <span class="font-medium text-gray-900">{{ $order->status->label() }}</span>.
        Status pembayaran dihitung otomatis dan tidak mengubah status pengerjaan.
    </p>

    @if ($errors->status->any())
        <ul class="mt-3 list-disc space-y-1 rounded-lg bg-red-50 px-8 py-2 text-sm text-red-800" role="alert">
            @foreach ($errors->status->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif

    @if (! empty($statusTargets))
        <form method="POST" action="{{ route('admin.orders.status.update', $order) }}"
            class="mt-3 grid gap-3 sm:grid-cols-[minmax(0,15rem)_1fr_auto] sm:items-end">
            @csrf
            @method('PATCH')

            <div>
                <label for="order-status-target" class="mb-1 block text-sm font-medium text-gray-700">Ubah status menjadi</label>
                <select id="order-status-target" name="status" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @foreach ($statusTargets as $value => $label)
                        <option value="{{ $value }}" @selected(old('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="order-status-note" class="mb-1 block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                <input type="text" id="order-status-note" name="note" value="{{ old('note') }}" maxlength="500"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>

            <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Simpan Status
            </button>
        </form>
    @elseif (! $canCancel)
        <p class="mt-3 text-sm text-gray-500">Status ini adalah status akhir dan tidak dapat diubah.</p>
    @endif

    @if ($canCancel)
        @include('admin.orders.modals.cancel-order')
    @endif
</section>
