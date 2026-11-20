{{-- PAGE-CAT-001 · Daftar kategori · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
@if ($categories->isNotEmpty())
    <nav aria-label="Kategori produk" class="my-6">
        <ul class="flex flex-wrap gap-2">
            <li>
                <a class="inline-block rounded-full border border-zinc-300 px-4 py-2 text-sm hover:bg-zinc-100"
                   @if (empty($filters['category'])) aria-current="page" @endif
                   href="{{ route('catalog.index', \Illuminate\Support\Arr::except($filters, 'category')) }}">Semua kategori</a>
            </li>
            @foreach ($categories as $entry)
                <li>
                    <a class="inline-block rounded-full border border-zinc-300 px-4 py-2 text-sm hover:bg-zinc-100"
                       @if (($filters['category'] ?? null) === $entry->slug) aria-current="true" @endif
                       href="{{ route('catalog.category', \Illuminate\Support\Arr::except($filters, 'category') + ['category' => $entry->slug]) }}">
                        {{ $entry->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
@endif