@if ($categories->isNotEmpty())
    @php
        $currentSlug = ($category ?? null)?->slug ?? ($filters['category'] ?? null);
        $allSelected = $currentSlug === null && request()->routeIs('catalog.index');
        $chip = 'inline-flex min-h-10 max-w-full items-center rounded-full border px-4 py-2 text-sm font-semibold break-words transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#17666B]';
        $active = 'border-[#5AC5CB] bg-[#5AC5CB] text-[#182F32]';
        $inactive = 'border-[#CAD4D0] bg-white text-[#344E50] hover:border-[#368F95] hover:bg-[#E4F5F3]';
        $otherFilters = \Illuminate\Support\Arr::except($filters, 'category');
    @endphp
    <nav aria-label="Kategori produk" class="mt-5">
        <ul class="flex flex-wrap gap-2">
            <li>
                <a class="{{ $chip }} {{ $allSelected ? $active : $inactive }}"
                   @if ($allSelected) aria-current="page" @endif
                   href="{{ route('catalog.index', $otherFilters) }}">Semua kategori</a>
            </li>
            @foreach ($categories as $entry)
                <li class="min-w-0 max-w-full">
                    <a class="{{ $chip }} {{ $currentSlug === $entry->slug ? $active : $inactive }}"
                       @if ($currentSlug === $entry->slug) aria-current="page" @endif
                       href="{{ route('catalog.category', ['category' => $entry->slug] + $otherFilters) }}">{{ $entry->name }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
@endif
