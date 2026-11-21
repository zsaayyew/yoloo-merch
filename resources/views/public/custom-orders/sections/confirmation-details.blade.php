{{--
    Section : Rincian konfirmasi custom order - PAGE-ORD-006
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Menampilkan kode REQ, ringkasan kebutuhan, dan kontak. Tanpa harga final.
    Status  : diimplementasikan.
--}}
<div class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-5">
    <div class="text-center">
        <p class="text-sm text-gray-500">Kode permintaan</p>
        <p class="mt-1 font-mono text-2xl font-bold tracking-wider text-gray-900">{{ $orderRequest->code }}</p>
        <p class="mt-1 text-sm text-gray-600">Status: {{ $orderRequest->status->label() }}</p>
    </div>

    <dl class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
        @if ($orderRequest->product_name_snapshot)
            <div class="sm:col-span-2">
                <dt class="text-gray-500">Referensi produk</dt>
                <dd class="font-medium text-gray-900">{{ $orderRequest->product_name_snapshot }}</dd>
            </div>
        @endif
        <div class="sm:col-span-2">
            <dt class="text-gray-500">Deskripsi kebutuhan</dt>
            <dd class="whitespace-pre-line font-medium text-gray-900">{{ $orderRequest->description }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Jumlah</dt>
            <dd class="font-medium text-gray-900">{{ number_format($orderRequest->quantity, 0, ',', '.') }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Tenggat yang diinginkan</dt>
            <dd class="font-medium text-gray-900">{{ $orderRequest->desired_deadline?->format('d/m/Y') ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Nama</dt>
            <dd class="font-medium text-gray-900">{{ $orderRequest->customer->name }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">WhatsApp</dt>
            <dd class="font-medium text-gray-900">+{{ $orderRequest->customer->whatsapp_number }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Dikirim pada</dt>
            <dd class="font-medium text-gray-900">{{ $orderRequest->submitted_at->format('d/m/Y H:i') }}</dd>
        </div>
    </dl>
</div>
