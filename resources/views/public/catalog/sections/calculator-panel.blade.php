{{-- PAGE-CAT-004 · Kalkulator estimasi · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
@php($estimate = session('catalog_estimate'))

<section id="kalkulator" aria-label="Kalkulator estimasi harga"
         class="space-y-3 rounded-xl border border-zinc-200 bg-white p-4">
    <x-catalog.estimate-note />

    <div data-catalog-estimate aria-live="polite" @if (!$estimate) hidden @endif>
        @if ($estimate)
            <dl class="grid gap-2">
                <div>
                    <dt>Estimasi per pcs</dt>
                    <dd class="font-semibold">Rp{{ number_format($estimate['unit_price'], 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt>Total estimasi</dt>
                    <dd class="text-xl font-bold">Rp{{ number_format($estimate['total'], 0, ',', '.') }}</dd>
                </div>
            </dl>

            @php($breakdown = $estimate['breakdown'])
            <ul class="mt-2 space-y-1 text-sm text-zinc-700">
                @if ($tier = $breakdown['quantity_tier'])
                    <li>Tier: {{ $tier['min'] }}{{ $tier['max'] ? '–' . $tier['max'] : '+' }} pcs</li>
                @endif
                @foreach ([
                    'option_surcharge' => 'Tambahan opsi per pcs',
                    'discount_per_unit' => 'Diskon per pcs',
                    'extra_fee' => 'Biaya tambahan',
                    'minimum_total_adjustment' => 'Penyesuaian minimum total',
                ] as $key => $label)
                    @if ($breakdown[$key] > 0)
                        <li>{{ $label }} Rp{{ number_format($breakdown[$key], 0, ',', '.') }}</li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>

    <button type="submit" name="intent" value="estimate" data-catalog-calculate
            class="rounded-lg border border-zinc-400 px-3 py-2 text-sm font-semibold">
        Hitung estimasi
    </button>
</section>