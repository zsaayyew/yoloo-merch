<header class="relative isolate overflow-hidden rounded-3xl bg-[#DDF3F0] px-6 py-7 sm:px-10 sm:py-9 lg:px-12">
    <img src="{{ asset('images/brand/yoloo-symbol.png') }}" alt="" width="468" height="426" class="absolute top-5 right-5 h-7 w-auto sm:hidden">
    <div class="relative z-10 min-w-0 sm:max-w-[68%]">
        <nav aria-label="Lokasi halaman" class="mb-4 flex flex-wrap items-center gap-2 text-xs font-semibold text-[#315A5B]">
            <a class="rounded underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-4" href="{{ route('home') }}">Beranda</a>
            <span aria-hidden="true">/</span>
            @if ($category)
                <a class="rounded underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-4" href="{{ route('catalog.index') }}">Katalog</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">Kategori</span>
            @else
                <span aria-current="page">Katalog</span>
            @endif
        </nav>
        <h1 class="max-w-full text-4xl leading-[1.04] font-black tracking-[-.055em] break-words text-[#182F32] sm:text-5xl lg:text-6xl">{{ $category?->name ?? 'Katalog Produk' }}</h1>
        @if (!$category)
            <p class="mt-3 max-w-lg text-sm leading-relaxed text-[#37595A] sm:text-base">Pilih produk untuk melihat spesifikasi, opsi kustomisasi, dan informasi harga.</p>
        @elseif (filled($category->description))
            <p class="mt-3 max-w-2xl text-sm leading-relaxed break-words whitespace-pre-line text-[#37595A] sm:text-base">{{ $category->description }}</p>
        @endif
    </div>

    <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 right-0 hidden w-[30%] items-center justify-center overflow-hidden sm:flex">
        <div class="absolute -right-14 -bottom-20 size-64 rounded-full border-[26px] border-[#5AC5CB]"></div>
        <div class="absolute top-2 left-[9%] size-11 rounded-full bg-[#EFC156]"></div>
        <div class="absolute bottom-7 left-[16%] h-5 w-12 -rotate-[28deg] rounded-full bg-[#E97E37]"></div>
        <div class="relative grid size-28 rotate-[9deg] place-items-center rounded-[1.4rem] border-2 border-[#182F32] bg-white shadow-[4px_5px_0_#182F32] lg:size-36">
            <img src="{{ asset('images/brand/yoloo-symbol.png') }}" alt="" width="468" height="426" class="h-[72%] w-[72%] object-contain">
        </div>
    </div>
</header>
