{{-- PAGE-CP-004: grid and lightbox markup khusus halaman ini; tanpa ketergantungan komponen bersama. --}}
<section aria-label="Foto galeri" class="bg-[#f8f8f8]">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        @if ($galleryItems->isEmpty())
            <div class="rounded-3xl border border-dashed border-[#181818]/20 bg-white px-6 py-12 text-center">
                <span aria-hidden="true" class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-[#58C0C8]/25 text-2xl">▧</span>
                <h2 class="mt-5 text-xl font-extrabold text-[#181818]">Galeri sedang disiapkan</h2>
                <p class="mx-auto mt-2 max-w-lg leading-7 text-[#181818]/70">Foto akan tampil setelah konten galeri dipublikasikan.</p>
                @if (\Illuminate\Support\Facades\Route::has('catalog.index'))
                    <a href="{{ route('catalog.index') }}" class="mt-6 inline-flex min-h-11 items-center rounded-full bg-[#14747b] px-5 py-3 text-sm font-bold text-white hover:bg-[#105d62]">Jelajahi katalog <span aria-hidden="true" class="ml-2">→</span></a>
                @endif
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-gallery-grid>
                @foreach ($galleryItems as $item)
                    <article data-gallery-item data-category="{{ $item['category_slug'] }}" class="overflow-hidden rounded-2xl border border-[#181818]/10 bg-white shadow-sm">
                        <button type="button" data-gallery-open data-full-src="{{ $item['image_url'] }}" data-alt="{{ $item['alt_text'] }}" data-caption="{{ $item['caption'] ?? '' }}" class="group block w-full text-left focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-[-4px] focus-visible:outline-[#14747b]">
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['alt_text'] }}" loading="lazy" class="aspect-[4/3] w-full object-cover transition duration-300 motion-safe:group-hover:scale-[1.03]">
                            @if (!empty($item['caption']))<span class="block px-4 py-3 text-sm font-semibold text-[#181818]">{{ $item['caption'] }}</span>@endif
                            <span class="sr-only">Perbesar gambar</span>
                        </button>
                    </article>
                @endforeach
            </div>
            <p hidden class="mt-5 rounded-2xl bg-white p-5 text-center text-[#181818]/70" data-gallery-empty role="status">Belum ada foto pada kategori ini. <button type="button" data-gallery-reset class="ml-1 font-bold text-[#124f53] underline underline-offset-2">Tampilkan semua</button></p>
        @endif
    </div>

    @if ($galleryItems->isNotEmpty())
        <dialog data-gallery-lightbox aria-label="Foto galeri diperbesar" class="m-auto w-[min(94vw,64rem)] max-w-none rounded-2xl border-0 bg-transparent p-0 text-white backdrop:bg-[#181818]/85 backdrop:backdrop-blur-sm">
            <div class="relative rounded-2xl bg-[#181818] p-3 shadow-2xl sm:p-5">
                <button type="button" data-gallery-close class="absolute right-5 top-5 z-10 grid h-11 w-11 place-items-center rounded-full bg-[#181818]/80 text-2xl font-bold text-white ring-1 ring-white/50 hover:bg-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white" aria-label="Tutup gambar">×</button>
                <img data-gallery-dialog-image src="" alt="" class="mx-auto max-h-[78vh] w-auto max-w-full rounded-lg object-contain">
                <p id="gallery-lightbox-caption" data-gallery-dialog-caption class="mt-3 text-sm text-white/80"></p>
            </div>
        </dialog>
    @endif
</section>
