{{--
    PAGE-FIN-002 - Form pembatalan transaksi (Owner)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Dibuka dengan elemen details (tanpa JavaScript). Variabel: $order.
              Alasan wajib. Payment dan refund yang sudah tercatat tidak dihapus (FIN-BR-009).
    Status  : diimplementasikan (FIN-FR-016).
--}}
<details class="mt-4 rounded-lg border border-red-200 bg-red-50" @if ($errors->cancel->any()) open @endif>
    <summary class="cursor-pointer px-4 py-2 text-sm font-medium text-red-800">Batalkan Transaksi (khusus Owner)</summary>

    <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" class="space-y-3 px-4 pb-4">
        @csrf

        <p class="text-sm text-red-900">
            Transaksi yang dibatalkan tidak masuk laporan penjualan dan tidak bisa dibuka lagi.
            Pembayaran yang sudah tercatat tetap tersimpan; pengembalian dana dicatat terpisah sebagai refund.
        </p>

        <div>
            <label for="cancel-reason" class="mb-1 block text-sm font-medium text-gray-700">Alasan pembatalan <span class="text-red-600">*</span></label>
            <textarea id="cancel-reason" name="reason" rows="2" maxlength="500" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('reason') }}</textarea>
            @foreach ($errors->cancel->get('reason') as $message)
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @endforeach
        </div>

        <button type="submit" class="rounded-lg bg-red-700 px-4 py-2 text-sm font-medium text-white hover:bg-red-800"
            onclick="return confirm('Batalkan transaksi ini? Tindakan ini tidak dapat dibatalkan.')">
            Batalkan Transaksi
        </button>
    </form>
</details>
