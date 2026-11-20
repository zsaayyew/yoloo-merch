{{-- CAT-FR-003 · Pencarian dan filter · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
@php($listUrl = $category ? route('catalog.category', ['category' => $category->slug]) : route('catalog.index'))

<form method="GET" action="{{ $listUrl }}" data-catalog-search
      class="my-6 grid gap-3 rounded-2xl bg-zinc-100 p-4 sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_auto] lg:items-end">
    <label class="grid gap-1 text-sm font-semibold">
        Cari produk
        <input class="rounded-lg border border-zinc-300 bg-white px-3 py-2" type="search"
               name="q" maxlength="100" value="{{ $filters['q'] ?? '' }}">
    </label>

    @if (!$category)
        <label class="grid gap-1 text-sm font-semibold">
            Kategori
            <select class="rounded-lg border border-zinc-300 bg-white px-3 py-2" name="category">
                <option value="">Semua kategori</option>
                @foreach ($categories as $entry)
                    <option value="{{ $entry->slug }}" @selected(($filters['category'] ?? '') === $entry->slug)>{{ $entry->name }}</option>
                @endforeach
            </select>
        </label>
    @endif

    <label class="grid gap-1 text-sm font-semibold">
        Model harga
        <select class="rounded-lg border border-zinc-300 bg-white px-3 py-2" name="price_model">
            <option value="">Semua model harga</option>
            @foreach (\App\Services\Catalog\CatalogData::PRICE_MODELS as $value => $label)
                <option value="{{ $value }}" @selected(($filters['price_model'] ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <div class="flex flex-wrap items-center gap-3">
        <button class="rounded-lg bg-zinc-950 px-4 py-2 font-semibold text-white" type="submit">Terapkan</button>
        @if ($filters !== [] || $categoryIgnored)
            <a class="text-sm underline" href="{{ $listUrl }}">Reset</a>
        @endif
    </div>

    <p data-catalog-search-status role="status" hidden class="text-sm text-zinc-700 sm:col-span-2">Memuat produk…</p>
</form>

@if ($categoryIgnored)
    <p role="status" class="mb-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-950">
        Kategori yang dipilih sudah tidak tersedia. Hasil ditampilkan tanpa filter kategori.
    </p>
@endif