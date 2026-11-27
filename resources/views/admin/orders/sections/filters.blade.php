{{--
    PAGE-FIN-001 - Section 'filters' (Daftar Order & Request)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Partial khusus halaman ini; di-include dari file halaman utama.
    Status  : diimplementasikan.
--}}
<form method="GET" action="{{ route('admin.orders.index') }}"
    class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-gray-200 bg-white p-4 sm:grid-cols-2 lg:grid-cols-6">
    <input type="hidden" name="tab" value="{{ $tab }}">

    <div class="lg:col-span-2">
        <label for="q" class="mb-1 block text-xs font-medium text-gray-600">Cari</label>
        <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}"
            placeholder="Kode, nama, atau nomor WhatsApp"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    </div>

    <div>
        <label for="status" class="mb-1 block text-xs font-medium text-gray-600">Status</label>
        <select id="status" name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="">Semua</option>
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    @if ($tab === 'orders')
        <div>
            <label for="payment_status" class="mb-1 block text-xs font-medium text-gray-600">Pembayaran</label>
            <select id="payment_status" name="payment_status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach ($paymentOptions as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['payment_status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div>
        <label for="channel" class="mb-1 block text-xs font-medium text-gray-600">Channel</label>
        <select id="channel" name="channel" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="">Semua</option>
            @foreach ($channelOptions as $value => $label)
                <option value="{{ $value }}" @selected(($filters['channel'] ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="from" class="mb-1 block text-xs font-medium text-gray-600">Dari tanggal</label>
        <input type="date" id="from" name="from" value="{{ $filters['from'] ?? '' }}"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    </div>

    <div>
        <label for="to" class="mb-1 block text-xs font-medium text-gray-600">Sampai tanggal</label>
        <input type="date" id="to" name="to" value="{{ $filters['to'] ?? '' }}"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    </div>

    <div class="flex items-end gap-2 lg:col-span-2">
        <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Terapkan</button>
        <a href="{{ route('admin.orders.index', ['tab' => $tab]) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset</a>
    </div>

    @if ($errors->any())
        <p class="text-sm text-red-600 sm:col-span-2 lg:col-span-6" role="alert">{{ $errors->first() }}</p>
    @endif
</form>
