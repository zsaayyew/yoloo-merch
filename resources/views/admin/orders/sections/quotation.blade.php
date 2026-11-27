{{--
    PAGE-FIN-002 - Section 'quotation' (Detail Request)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Menampilkan penawaran, form isi/revisi penawaran (saat boleh), dan tautan kirim via WhatsApp.
              Menyimpan penawaran otomatis mengubah status menjadi "Penawaran Dikirim".
    Status  : diimplementasikan.
--}}
@use('App\Support\Money')

<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Penawaran</h2>

    @if ($orderRequest->quotation_amount === null)
        <p class="text-sm text-gray-500">Penawaran belum dibuat.</p>
    @else
        <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-gray-500">Nominal penawaran</dt>
                <dd class="font-medium text-gray-900">{{ Money::format($orderRequest->quotation_amount) }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Syarat penawaran</dt>
                <dd class="whitespace-pre-line text-gray-900">{{ $orderRequest->quotation_terms ?? '-' }}</dd>
            </div>
        </dl>

        @if ($customer)
            @php
                $waText = "Halo {$customer->name}, berikut penawaran untuk request {$orderRequest->code}:\n"
                    .'Total: '.Money::format($orderRequest->quotation_amount)."\n"
                    .($orderRequest->quotation_terms ? "Syarat: {$orderRequest->quotation_terms}\n" : '')
                    .'Mohon konfirmasi jika setuju. Terima kasih.';
            @endphp
            <a href="https://wa.me/{{ $customer->whatsapp_number }}?text={{ rawurlencode($waText) }}"
                target="_blank" rel="noopener"
                class="mt-3 inline-flex rounded-lg border border-green-600 px-3 py-1.5 text-sm font-medium text-green-700 hover:bg-green-50">
                Kirim penawaran lewat WhatsApp
            </a>
        @endif
    @endif

    @if ($canQuote)
        <form method="POST" action="{{ route('admin.requests.quotation.update', $orderRequest) }}"
            class="mt-4 space-y-3 border-t border-gray-100 pt-4">
            @csrf
            @method('PUT')

            <h3 class="text-sm font-medium text-gray-700">{{ $orderRequest->quotation_amount === null ? 'Buat penawaran' : 'Revisi penawaran' }}</h3>

            <div>
                <label for="quotation_amount" class="mb-1 block text-xs font-medium text-gray-600">Nominal (Rupiah) <span class="text-red-600">*</span></label>
                <input type="text" id="quotation_amount" name="quotation_amount" inputmode="numeric" required
                    value="{{ old('quotation_amount', $orderRequest->quotation_amount) }}" placeholder="1500000"
                    class="w-full max-w-xs rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('quotation_amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="quotation_terms" class="mb-1 block text-xs font-medium text-gray-600">Syarat penawaran</label>
                <textarea id="quotation_terms" name="quotation_terms" rows="3" maxlength="2000"
                    placeholder="Contoh: DP 50%, pengerjaan 7 hari kerja setelah DP"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('quotation_terms', $orderRequest->quotation_terms) }}</textarea>
                @error('quotation_terms') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Simpan Penawaran</button>
        </form>
    @elseif ($orderRequest->quotation_amount === null)
        <p class="mt-2 text-xs text-gray-500">Penawaran bisa dibuat setelah request berstatus "Ditinjau Admin".</p>
    @endif
</section>
