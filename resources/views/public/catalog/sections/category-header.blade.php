{{-- PAGE-CAT-002 · Header kategori · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
<header>
    <a class="text-sm font-semibold underline" href="{{ route('catalog.index') }}">Semua produk</a>
    <h1 class="mt-4 text-4xl font-bold tracking-tight">{{ $category->name }}</h1>
    @if ($category->description)
        <p class="mt-2 max-w-2xl whitespace-pre-line text-zinc-700">{{ $category->description }}</p>
    @endif
</header>