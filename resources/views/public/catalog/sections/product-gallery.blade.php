{{-- CAT-FR-011 · Galeri foto · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
<section data-catalog-gallery aria-label="Foto produk" class="grid grid-cols-3 gap-2.5 sm:grid-cols-4 sm:gap-3">
    @forelse ($images->get($product->id, collect()) as $image)
        @php($url = \Illuminate\Support\Facades\Storage::disk('public')->url($image->path))
        <figure class="{{ $loop->first ? 'col-span-full' : 'min-w-0' }}">
            <a href="{{ $url }}" data-catalog-photo
               aria-label="{{ 'Perbesar foto ' . $loop->iteration . ' — ' . $product->name }}"
               @class([
                   'group relative block overflow-hidden border border-[#d4deda] bg-white [&:not([href])_.catalog-zoom]:hidden',
                   'rounded-3xl aspect-[4/3]' => $loop->first,
                   'rounded-xl aspect-square' => ! $loop->first,
               ])>
                <img src="{{ $url }}" width="{{ $loop->first ? 640 : 96 }}" height="{{ $loop->first ? 480 : 96 }}"
                     class="h-full w-full object-cover motion-safe:transition-transform motion-safe:duration-300 motion-safe:group-hover:scale-[1.02]"
                     alt="{{ $image->alt_text ?: $product->name . ' — foto ' . $loop->iteration }}"
                     loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                <span data-catalog-no-photo hidden class="absolute inset-0">
                    <x-catalog.photo-placeholder label="Foto tidak tersedia" />
                </span>
                @if ($loop->first)
                    <span class="catalog-zoom absolute right-4 bottom-4 inline-flex items-center gap-2 rounded-full bg-[#182F32] px-3.5 py-2 text-xs font-bold text-white">
                        <span aria-hidden="true" class="text-base leading-none">↗</span> Perbesar foto
                    </span>
                @endif
            </a>
        </figure>
    @empty
        <div class="col-span-full aspect-[4/3] overflow-hidden rounded-3xl border border-[#d4deda]">
            <x-catalog.photo-placeholder label="Foto belum tersedia" />
        </div>
    @endforelse

    <dialog data-catalog-lightbox aria-label="Pembesaran foto produk"
            class="m-auto max-h-[94dvh] max-w-[94vw] overflow-auto rounded-3xl bg-[#FAF9F5] p-3 text-[#182F32] shadow-2xl backdrop:bg-[#102629]/85 sm:p-5">
        <form method="dialog" class="mb-3 text-right">
            <button autofocus class="min-h-11 rounded-full bg-[#182F32] px-5 text-sm font-bold text-white hover:bg-[#29474b]">Tutup</button>
        </form>
        <img data-catalog-lightbox-image class="mx-auto max-h-[76dvh] max-w-full rounded-xl object-contain" alt="">
    </dialog>
</section>
