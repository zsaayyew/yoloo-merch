{{-- PAGE-CP-001: panduan menjelajah, tanpa klaim keunggulan bisnis yang belum disahkan. --}}
<section aria-labelledby="home-advantages-title" class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <div class="grid gap-8 md:grid-cols-[0.9fr_1.1fr] md:items-end">
            <div>
                <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#c75d1d]">Mulai dari sini</p>
                <h2 id="home-advantages-title" class="mt-3 text-3xl font-black tracking-tight text-[#181818] sm:text-4xl">Informasi produk, satu langkah lebih dekat.</h2>
            </div>
            <p class="max-w-2xl leading-7 text-[#181818]/70">Katalog membantu Anda menelusuri pilihan yang tersedia. Buka halaman produk untuk membaca detail, opsi, dan informasi harga yang dikelola pada katalog.</p>
        </div>
        <ol class="mt-9 grid gap-4 md:grid-cols-3">
            @foreach ([['01', 'Jelajahi katalog', 'Telusuri produk dan kategori yang tersedia.'], ['02', 'Buka detail produk', 'Baca deskripsi dan opsi yang tercantum pada halaman produk.'], ['03', 'Pilih langkah berikutnya', 'Ikuti jalur pemesanan yang tersedia atau hubungi YOLOO Merch.']] as [$number, $title, $description])
                <li class="rounded-2xl border border-[#181818]/10 bg-[#f8f8f8] p-6 sm:p-7">
                    <span class="inline-grid h-9 w-9 place-items-center rounded-full bg-[#E8C050] text-sm font-black text-[#181818]">{{ $number }}</span>
                    <h3 class="mt-4 text-lg font-extrabold text-[#181818]">{{ $title }}</h3>
                    <p class="mt-2 leading-7 text-[#181818]/70">{{ $description }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
