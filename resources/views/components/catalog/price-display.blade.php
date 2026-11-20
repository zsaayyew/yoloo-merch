{{-- CAT-FR-007 · Tampilan model harga · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
@props(['product', 'pricing' => null])
@inject('catalogData', \App\Services\Catalog\CatalogData::class)

@php
    $model = $catalogData->priceModel($product);
    $price = $catalogData->displayPrice($product, $pricing);
@endphp

<div class="space-y-1 text-sm">
    <p class="font-semibold text-zinc-900">{{ $catalogData::PRICE_MODELS[$model] }}</p>
    @if ($price !== null)
        <p class="text-base font-semibold text-zinc-950">Rp{{ number_format($price, 0, ',', '.') }} / pcs</p>
    @elseif ($model === 'calculator')
        <p class="text-zinc-700">Pilih opsi dan jumlah untuk melihat estimasi.</p>
    @elseif ($model !== 'quotation')
        <p class="text-zinc-700">Tarif belum tersedia. Silakan minta penawaran.</p>
    @endif
</div>