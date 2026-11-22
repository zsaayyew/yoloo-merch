{{--
    Section : Langkah berikutnya - PAGE-ORD-004
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Tidak ada instruksi pembayaran; QRIS dikirim admin manual via WhatsApp setelah harga final.
              Tombol WhatsApp (ORD-FR-016) tampil hanya jika nomor WhatsApp usaha sudah diatur ADM.
    Status  : diimplementasikan.
--}}
<div class="rounded-xl border border-gray-200 p-5">
    <h2 class="font-semibold text-gray-900">Langkah berikutnya</h2>
    <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-gray-700">
        <li>Admin YOLOO Merch akan memeriksa pesananmu.</li>
        <li>Admin menghubungimu lewat WhatsApp untuk konfirmasi harga final dan pembayaran.</li>
        <li>Setelah pesanan siap, ambil di lokasi YOLOO Merch dengan menyebutkan kode pesanan.</li>
    </ol>

    @if ($whatsappUrl)
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
            class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700">
            Hubungi Admin via WhatsApp
        </a>
        <p class="mt-2 text-xs text-gray-500">Pesan otomatis berisi kode {{ $order->code }} dan namamu.</p>
    @endif
</div>
