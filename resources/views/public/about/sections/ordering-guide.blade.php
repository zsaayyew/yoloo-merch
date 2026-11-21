{{-- PAGE-CP-002: langkah eksplorasi website; pemesanan tetap ditangani CAT/ORD. --}}
<section aria-labelledby="about-ordering-guide-title" class="bg-[#f8f8f8]">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#14747b]">Panduan awal</p>
        <h2 id="about-ordering-guide-title" class="mt-3 max-w-2xl text-3xl font-black tracking-tight text-[#181818] sm:text-4xl">Jelajahi pilihan, lalu hubungi kami.</h2>
        <ol class="mt-8 grid gap-4 md:grid-cols-3">
            @foreach ([['01', 'Buka katalog', 'Lihat pilihan produk yang tersedia.'], ['02', 'Pelajari detail', 'Baca informasi dan variasi pada halaman produk.'], ['03', 'Pilih jalur berikutnya', 'Ajukan pesanan custom melalui formulir website bila tersedia atau lihat informasi pada halaman kontak.']] as [$number, $title, $description])
                <li class="rounded-2xl border border-[#181818]/10 bg-white p-6"><span class="text-sm font-black text-[#14747b]">{{ $number }}</span><h3 class="mt-3 text-lg font-extrabold text-[#181818]">{{ $title }}</h3><p class="mt-2 leading-7 text-[#181818]/70">{{ $description }}</p></li>
            @endforeach
        </ol>
        <div class="mt-8 flex flex-wrap gap-3">
            @if (\Illuminate\Support\Facades\Route::has('catalog.index'))
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center rounded-full bg-[#14747b] px-5 py-3 text-sm font-bold text-white hover:bg-[#105d62] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#181818]">Jelajahi Katalog <span aria-hidden="true" class="ml-2">→</span></a>
            @endif
            @if (\Illuminate\Support\Facades\Route::has('custom-orders.create'))
                <a href="{{ route('custom-orders.create') }}" class="inline-flex items-center rounded-full border-2 border-[#181818] px-5 py-3 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">Ajukan Pesanan Custom</a>
            @endif
            <a href="{{ route('contact') }}" class="inline-flex items-center rounded-full border-2 border-[#181818] px-5 py-3 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">Lihat kanal kontak</a>
        </div>
    </div>
</section>
