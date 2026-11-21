{{--
    Section : Persetujuan & kirim pesanan - PAGE-ORD-003
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Persetujuan wajib, token sekali pakai, honeypot, tombol dikunci setelah diklik.
    Status  : diimplementasikan.
--}}
<form method="POST" action="{{ route('checkout.submit') }}" data-submit-guard
    class="space-y-4 rounded-xl border border-gray-200 bg-gray-50 p-5 lg:sticky lg:top-6">
    @csrf
    <input type="hidden" name="submission_token" value="{{ $submissionToken }}">

    <div class="hidden" aria-hidden="true">
        <label for="website">Jangan diisi</label>
        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
    </div>

    <div class="flex justify-between">
        <span class="font-semibold text-gray-900">Total estimasi</span>
        <span class="text-lg font-bold text-gray-900">{{ \App\Support\Money::format($estimated_total) }}</span>
    </div>

    <label class="flex items-start gap-3 text-sm text-gray-700">
        <input type="checkbox" name="agreement" value="1" required
            class="mt-1 h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
        <span>
            Saya memahami bahwa total di atas adalah <strong>estimasi</strong>. Harga final dan cara pembayaran
            akan dikonfirmasi admin melalui WhatsApp, dan pesanan diambil langsung (pickup).
        </span>
    </label>
    @error('agreement')
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror

    <button type="submit" data-loading-text="Mengirim pesanan..."
        class="flex w-full items-center justify-center rounded-lg bg-gray-900 px-6 py-3 font-semibold text-white hover:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-60">
        Kirim Pesanan
    </button>
</form>
