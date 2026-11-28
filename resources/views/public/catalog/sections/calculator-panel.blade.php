{{-- PAGE-CAT-004 · Kalkulator estimasi · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
@php($estimate = session('catalog_estimate'))

<section id="kalkulator" aria-label="Kalkulator estimasi harga"
         class="scroll-mt-24 space-y-4 rounded-2xl border-t-4 border-[#EFC156] bg-[#fff8e1] p-4 text-[#182F32] sm:p-5">
    <x-catalog.estimate-note />

    <div data-catalog-estimate aria-live="polite" @if (!$estimate) hidden @endif
         class="[&_dd]:mt-1 [&_dd]:font-black [&_dd]:tabular-nums [&_dl]:gap-3 [&_dl>div]:min-w-0 [&_dl>div]:rounded-xl [&_dl>div]:bg-white/80 [&_dl>div]:p-3 [&_dt]:text-xs [&_dt]:font-bold [&_dt]:text-[#465b5d] sm:[&_dl]:grid-cols-2">
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
            class="min-h-11 rounded-full border border-[#7e693b] bg-white px-4 py-2 text-sm font-bold text-[#50401e] hover:bg-[#fff4cc]">
        Hitung estimasi
    </button>
</section>
