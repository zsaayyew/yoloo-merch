{{--
    PAGE-FIN-005 - Form catat refund (Owner)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Dibuka dengan elemen details (tanpa JavaScript). Variabel: $payment, $max.
              Dicatat setelah pengembalian dana benar-benar dilakukan di luar sistem.
    Status  : diimplementasikan (FIN-FR-019).
--}}
<details class="mt-4 rounded-lg border border-gray-200 bg-gray-50">
    <summary class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-900">
        Catat refund dari pembayaran {{ $payment->payment_date?->format('d/m/Y') }}
        (Rp{{ number_format((int) $payment->amount, 0, ',', '.') }}),
        maksimum Rp{{ number_format((int) $max, 0, ',', '.') }}
    </summary>

    <form method="POST" action="{{ route('admin.payments.refunds.store', $payment) }}" class="grid gap-3 px-4 pb-4 sm:grid-cols-2">
        @csrf
        <input type="hidden" name="submission_key" value="{{ old('submission_key', \Illuminate\Support\Str::uuid()->toString()) }}">

        <div>
            <label for="refund-amount-{{ $payment->id }}" class="mb-1 block text-sm font-medium text-gray-700">Nominal refund (Rupiah) <span class="text-red-600">*</span></label>
            <input type="number" id="refund-amount-{{ $payment->id }}" name="amount" min="1" max="{{ (int) $max }}" step="1" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label for="refund-date-{{ $payment->id }}" class="mb-1 block text-sm font-medium text-gray-700">Tanggal refund <span class="text-red-600">*</span></label>
            <input type="date" id="refund-date-{{ $payment->id }}" name="refund_date" required value="{{ now()->format('Y-m-d') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label for="refund-method-{{ $payment->id }}" class="mb-1 block text-sm font-medium text-gray-700">Metode pengembalian <span class="text-red-600">*</span></label>
            <input type="text" id="refund-method-{{ $payment->id }}" name="method" maxlength="50" required placeholder="Contoh: transfer, tunai"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label for="refund-reference-{{ $payment->id }}" class="mb-1 block text-sm font-medium text-gray-700">Referensi (opsional)</label>
            <input type="text" id="refund-reference-{{ $payment->id }}" name="reference" maxlength="100"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2">
            <label for="refund-reason-{{ $payment->id }}" class="mb-1 block text-sm font-medium text-gray-700">Alasan refund <span class="text-red-600">*</span></label>
            <textarea id="refund-reason-{{ $payment->id }}" name="reason" rows="2" maxlength="500" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></textarea>
        </div>

        <div class="sm:col-span-2">
            <p class="mb-2 text-xs text-gray-500">Sistem tidak mengirim uang. Catat refund setelah dana benar-benar dikembalikan.</p>
            <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Simpan Refund</button>
        </div>
    </form>
</details>
