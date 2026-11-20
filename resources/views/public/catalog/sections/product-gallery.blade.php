{{-- CAT-FR-011 · Galeri foto · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
<div data-catalog-gallery aria-label="Foto produk">
    @forelse ($images->get($product->id, collect()) as $image)
        @php($url = \Illuminate\Support\Facades\Storage::disk('public')->url($image->path))
        <figure class="{{ $loop->first ? 'mb-3' : 'mt-2 inline-block w-24 align-top' }}">
            <a href="{{ $url }}" data-catalog-photo class="block overflow-hidden rounded-xl bg-zinc-100">
                <img src="{{ $url }}" width="{{ $loop->first ? 640 : 96 }}" height="{{ $loop->first ? 480 : 96 }}"
                     class="{{ $loop->first ? 'aspect-[4/3]' : 'aspect-square' }} w-full object-cover"
                     alt="{{ $image->alt_text ?: $product->name . ' — foto ' . $loop->iteration }}"
                     loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                <span data-catalog-no-photo hidden class="block p-3 text-center text-sm text-zinc-700">
                    Foto tidak tersedia
                </span>
            </a>
        </figure>
    @empty
        <div class="grid aspect-[4/3] place-items-center rounded-2xl bg-zinc-100 text-sm text-zinc-700">
            Foto belum tersedia
        </div>
    @endforelse

    <dialog data-catalog-lightbox aria-label="Pembesaran foto produk"
            class="m-auto max-h-[90vh] max-w-[90vw] rounded-2xl bg-white p-4 backdrop:bg-black/70">
        <form method="dialog" class="mb-3 text-right">
            <button class="rounded-lg bg-zinc-950 px-4 py-2 text-white">Tutup</button>
        </form>
        <img data-catalog-lightbox-image class="max-h-[70vh] max-w-full object-contain" alt="">
    </dialog>
</div>