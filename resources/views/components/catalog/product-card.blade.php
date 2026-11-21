{{-- CAT-FR-002 · Kartu produk untuk katalog dan beranda · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
@props(['product', 'image' => null, 'pricing' => null])

<article {{ $attributes->class('overflow-hidden rounded-2xl border border-zinc-200 bg-white') }}>
    <a class="block h-full hover:bg-zinc-50 focus-visible:outline focus-visible:outline-offset-[-3px]"
       href="{{ route('products.show', ['product' => $product->slug]) }}">
        <div class="grid aspect-[4/3] place-items-center bg-zinc-100 text-sm text-zinc-700">
            @if ($image)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->path) }}"
                     alt="{{ $image->alt_text ?: 'Foto ' . $product->name }}" width="480" height="360" loading="lazy"
                     class="aspect-[4/3] w-full object-cover">
                <span data-catalog-no-photo hidden class="p-3 text-center">Foto tidak tersedia</span>
            @else
                <span>Foto belum tersedia</span>
            @endif
        </div>
        <div class="space-y-2 p-4">
            <h3 class="text-lg font-bold text-zinc-950">{{ $product->name }}</h3>
            <x-catalog.price-display :product="$product" :pricing="$pricing" />
        </div>
    </a>
</article>