{{--
    Section : Persetujuan pengajuan - PAGE-ORD-005
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Wajib dicentang sebelum kirim (ORD-FR-012).
    Status  : diimplementasikan.
--}}
<div>
    <label class="flex items-start gap-3 rounded-lg border border-gray-200 p-4">
        <input type="checkbox" name="agreement" value="1" required @checked(old('agreement'))
            class="mt-1 h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
        <span class="text-sm text-gray-700">
            Saya memahami bahwa ini adalah <strong>permintaan penawaran</strong>, bukan pesanan final.
            Harga dan detail akan dikonfirmasi admin melalui WhatsApp, dan pesanan diambil langsung (pickup).
        </span>
    </label>
    @error('agreement')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
