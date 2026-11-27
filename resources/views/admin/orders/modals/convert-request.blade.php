{{--
    PAGE-FIN-002 - Konversi request menjadi order (FIN)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Dibuat sebagai panel <details> (tanpa x-ui.modal milik ADM). Konversi hanya sekali:
              dijaga RequestConversionService (lock + cek order sumber + unique source_request_id).
    Status  : diimplementasikan.
--}}
@use('App\Support\Money')

<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Konversi ke Order</h2>

    @if ($orderRequest->convertedOrder)
        <p class="text-sm text-gray-700">
            Request ini sudah dikonversi menjadi order
            <a href="{{ route('admin.orders.show', $orderRequest->convertedOrder) }}" class="font-medium text-blue-700 hover:underline">{{ $orderRequest->convertedOrder->code }}</a>.
            Konversi hanya dapat dilakukan satu kali.
        </p>
    @elseif ($canConvert)
        <details class="group" @if ($errors->has('agreed_dp_amount') || $errors->has('payment_terms_note')) open @endif>
            <summary class="inline-flex cursor-pointer rounded-lg bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-600">
                Konversi ke Order
            </summary>

            <form method="POST" action="{{ route('admin.requests.convert', $orderRequest) }}"
                class="mt-4 space-y-3 rounded-lg border border-gray-200 p-4"
                onsubmit="return confirm('Konversi request ini menjadi order? Tindakan ini hanya bisa dilakukan satu kali.')">
                @csrf

                <p class="text-sm text-gray-700">
                    Order baru dibuat dengan total <strong>{{ Money::format($orderRequest->quotation_amount) }}</strong>
                    (sesuai penawaran), status "Menunggu Pembayaran", dan pengambilan di tempat.
                </p>

                <div>
                    <label for="agreed_dp_amount" class="mb-1 block text-xs font-medium text-gray-600">DP yang disepakati (Rupiah, opsional)</label>
                    <input type="text" id="agreed_dp_amount" name="agreed_dp_amount" inputmode="numeric"
                        value="{{ old('agreed_dp_amount') }}" placeholder="750000"
                        class="w-full max-w-xs rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('agreed_dp_amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="payment_terms_note" class="mb-1 block text-xs font-medium text-gray-600">Catatan pembayaran (opsional)</label>
                    <textarea id="payment_terms_note" name="payment_terms_note" rows="2" maxlength="2000"
                        placeholder="Kosongkan untuk memakai syarat penawaran"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('payment_terms_note') }}</textarea>
                    @error('payment_terms_note') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="rounded-lg bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-600">Buat Order</button>
            </form>
        </details>
    @else
        <p class="text-sm text-gray-500">{{ $convertBlocker ?? 'Request ini tidak dapat dikonversi.' }}</p>
    @endif
</section>
