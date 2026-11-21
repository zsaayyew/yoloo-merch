@php
    $listUrl = $category ? route('catalog.category', ['category' => $category->slug]) : route('catalog.index');
    $control = 'h-11 w-full min-w-0 rounded-lg border border-[#B5C5C1] bg-white px-3 text-base font-normal text-[#182F32] focus-visible:border-[#17666B] focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-[#17666B] sm:text-sm';
@endphp

<form method="GET" action="{{ $listUrl }}" data-catalog-search
      class="mt-5 grid min-w-0 grid-cols-2 gap-3 rounded-2xl border border-[#DADFDA] bg-white p-4 sm:grid-cols-2 sm:p-5 lg:flex lg:items-end">
    <label class="col-span-2 grid min-w-0 gap-1.5 text-sm font-bold sm:col-span-1 lg:flex-[2]">
        Cari produk
        <input class="{{ $control }}" type="search" name="q" maxlength="100"
               value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama atau deskripsi">
    </label>

    @if (!$category)
        <label class="grid min-w-0 gap-1.5 text-sm font-bold lg:flex-1">
            Kategori
            <select class="{{ $control }}" name="category">
                <option value="">Semua kategori</option>
                @foreach ($categories as $entry)
                    <option value="{{ $entry->slug }}" @selected(($filters['category'] ?? '') === $entry->slug)>{{ $entry->name }}</option>
                @endforeach
            </select>
        </label>
    @endif

    <label class="grid min-w-0 gap-1.5 text-sm font-bold lg:flex-1">
        Model harga
        <select class="{{ $control }}" name="price_model">
            <option value="">Semua model harga</option>
            @foreach (\App\Services\Catalog\CatalogData::PRICE_MODELS as $value => $label)
                <option value="{{ $value }}" @selected(($filters['price_model'] ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <div class="col-span-2 flex items-end gap-3 sm:col-span-1">
        <button class="min-h-11 grow rounded-lg bg-[#182F32] px-5 py-2 text-sm font-bold text-white hover:bg-[#235459] focus-visible:outline-2 focus-visible:outline-offset-3 focus-visible:outline-[#17666B] lg:grow-0" type="submit">Terapkan</button>
        @if ($filters !== [] || $categoryIgnored)
            <a class="inline-flex min-h-11 items-center rounded px-1 text-sm font-semibold underline underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-2" href="{{ $listUrl }}">Reset</a>
        @endif
    </div>
    <p data-catalog-search-status role="status" hidden class="text-sm font-semibold text-[#17666B] col-span-full">Memuat produk…</p>
</form>

@if ($categoryIgnored)
    <p role="status" class="mt-3 rounded-xl border-l-4 border-[#EFC156] bg-[#FFF6D9] px-4 py-3 text-sm text-[#483914]">
        Kategori yang dipilih sudah tidak tersedia. Hasil ditampilkan tanpa filter kategori.
    </p>
@endif
