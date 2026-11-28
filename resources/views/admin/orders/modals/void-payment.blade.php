{{--
    PAGE-FIN-005 - Form Void pembayaran (Owner)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Dibuka dengan elemen details (tanpa JavaScript). Variabel: $payment.
              Void bukan refund: data tidak dihapus dan alasan wajib (FIN-FR-009).
    Status  : diimplementasikan (FIN-FR-009).
--}}
<details class="rounded-lg border border-red-200 bg-red-50">
    <summary class="cursor-pointer px-3 py-1 text-xs font-medium text-red-800">Void</summary>

    <form method="POST" action="{{ route('admin.payments.void', $payment) }}" class="space-y-2 px-3 pb-3">
        @csrf
        <label for="void-reason-{{ $payment->id }}" class="block text-xs font-medium text-gray-700">Alasan Void <span class="text-red-600">*</span></label>
        <textarea id="void-reason-{{ $payment->id }}" name="reason" rows="2" maxlength="500" required
            class="w-full rounded-lg border border-gray-300 px-2 py-1 text-xs"></textarea>
        <button type="submit" class="rounded-lg bg-red-700 px-3 py-1 text-xs font-medium text-white hover:bg-red-800"
            onclick="return confirm('Void pembayaran ini? Data tetap tersimpan dengan status Void.')">
            Void Pembayaran
        </button>
    </form>
</details>
