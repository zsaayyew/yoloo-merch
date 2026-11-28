{{--
    PAGE-FIN-005 - Form tambah pembayaran QRIS
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Dibuka dengan elemen details (tanpa JavaScript). Variabel: $order, $ledger.
              Metode versi pertama hanya QRIS. submission_key mencegah pengiriman ganda.
    Status  : diimplementasikan (FIN-FR-008).
--}}
@php $remaining = (int) ($ledger['remaining'] ?? 0); @endphp
<details class="mt-4 rounded-lg border border-gray-200 bg-gray-50" @if ($errors->payment->any()) open @endif>
    <summary class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-900">Tambah Pembayaran QRIS</summary>

    <form method="POST" action="{{ route('admin.orders.payments.store', $order) }}" class="grid gap-3 px-4 pb-4 sm:grid-cols-2">
        @csrf
        <input type="hidden" name="submission_key" value="{{ old('submission_key', \Illuminate\Support\Str::uuid()->toString()) }}">

        <div>
            <label for="payment-amount" class="mb-1 block text-sm font-medium text-gray-700">Nominal (Rupiah) <span class="text-red-600">*</span></label>
            <input type="number" id="payment-amount" name="amount" min="1" max="{{ $remaining }}" step="1" required
                value="{{ old('amount') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <p class="mt-1 text-xs text-gray-500">Maksimum sebesar sisa tagihan: Rp{{ number_format($remaining, 0, ',', '.') }}.</p>
        </div>

        <div>
            <label for="payment-date" class="mb-1 block text-sm font-medium text-gray-700">Tanggal bayar <span class="text-red-600">*</span></label>
            <input type="date" id="payment-date" name="payment_date" required
                value="{{ old('payment_date', now()->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label for="payment-reference" class="mb-1 block text-sm font-medium text-gray-700">Referensi transaksi QRIS (opsional)</label>
            <input type="text" id="payment-reference" name="reference" maxlength="100"
                value="{{ old('reference') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label for="payment-note" class="mb-1 block text-sm font-medium text-gray-700">Keterangan (DP, bertahap, pelunasan)</label>
            <input type="text" id="payment-note" name="note" maxlength="500"
                value="{{ old('note') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2">
            <p class="mb-2 text-xs text-gray-500">Catat hanya pembayaran yang sudah Anda verifikasi manual di aplikasi QRIS atau mutasi rekening.</p>
            <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Simpan Pembayaran</button>
        </div>
    </form>
</details>
