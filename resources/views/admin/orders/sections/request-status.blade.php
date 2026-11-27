{{--
    PAGE-FIN-002 - Section 'request-status' (Detail Request)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Tampilan status + form ubah status (review). Pilihan status hanya yang diizinkan
              StatusTransitionService. "Penawaran Dikirim" lewat form penawaran, "Dikonversi" lewat konversi.
    Status  : diimplementasikan.
--}}
<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Status Request</h2>

    <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">
        <div>
            <dt class="text-gray-500">Status</dt>
            <dd class="mt-1">@include('admin.orders.sections.status-pill', ['enum' => $orderRequest->status])</dd>
        </div>
        <div>
            <dt class="text-gray-500">Diajukan</dt>
            <dd class="font-medium text-gray-900">{{ $orderRequest->submitted_at?->format('d/m/Y H:i') }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Order hasil konversi</dt>
            <dd class="font-medium text-gray-900">
                @if ($orderRequest->convertedOrder)
                    <a href="{{ route('admin.orders.show', $orderRequest->convertedOrder) }}" class="text-blue-700 hover:underline">{{ $orderRequest->convertedOrder->code }}</a>
                @else
                    Belum dikonversi
                @endif
            </dd>
        </div>
    </dl>

    <div class="mt-4 border-t border-gray-100 pt-4">
        @if (count($manualTargets))
            <form method="POST" action="{{ route('admin.requests.status.update', $orderRequest) }}"
                class="flex flex-wrap items-end gap-3"
                onsubmit="return confirm('Ubah status request ini? Status Ditolak dan Kedaluwarsa tidak bisa dibatalkan.')">
                @csrf
                @method('PATCH')

                <div>
                    <label for="status" class="mb-1 block text-xs font-medium text-gray-600">Ubah status ke</label>
                    <select id="status" name="status" required class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option value="">Pilih status</option>
                        @foreach ($manualTargets as $value => $label)
                            <option value="{{ $value }}" @selected(old('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[16rem] flex-1">
                    <label for="reason" class="mb-1 block text-xs font-medium text-gray-600">Alasan (wajib untuk Ditolak dan Kedaluwarsa)</label>
                    <input type="text" id="reason" name="reason" maxlength="1000" value="{{ old('reason') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                </div>
                <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Simpan Status</button>
                @error('status') <p class="w-full text-sm text-red-600">{{ $message }}</p> @enderror
                @error('reason') <p class="w-full text-sm text-red-600">{{ $message }}</p> @enderror
            </form>

            <p class="mt-2 text-xs text-gray-500">
                Status "Penawaran Dikirim" diatur lewat form Penawaran, dan "Dikonversi" lewat tombol Konversi ke Order.
            </p>
        @else
            <p class="text-sm text-gray-500">
                Status ini tidak dapat diubah manual lagi
                @if ($canQuote) (lanjutkan dengan mengisi penawaran). @elseif ($canConvert) (lanjutkan dengan konversi ke order). @else . @endif
            </p>
        @endif
    </div>
</section>
