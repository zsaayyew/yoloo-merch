{{-- PAGE-CP-001: Hero menggunakan aset brand resmi; tidak memuat klaim bisnis terukur. --}}
<section aria-labelledby="home-hero-title" class="overflow-hidden bg-[#E87830] text-[#181818]">
    <div class="mx-auto grid max-w-7xl items-center gap-8 px-4 py-14 sm:px-6 sm:py-20 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-24">
        <div class="relative z-10 max-w-2xl">
            <p class="inline-flex rounded-full border border-[#181818]/20 bg-white/50 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em]">Your Personal Merchandise Solution</p>
            <h1 id="home-hero-title" class="mt-6 text-4xl font-black leading-[1.05] tracking-tight sm:text-5xl lg:text-6xl">Bawa idemu menjadi merchandise.</h1>
            <p class="mt-5 max-w-xl text-base leading-7 sm:text-lg sm:leading-8">Jelajahi layanan merchandise custom dan printing, lalu temukan pilihan yang tersedia di katalog YOLOO Merch.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                @if (\Illuminate\Support\Facades\Route::has('catalog.index'))
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#181818] px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#333] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Jelajahi Katalog <span aria-hidden="true" class="ml-2">→</span></a>
                @endif
                <a href="{{ route('about') }}" class="inline-flex items-center justify-center rounded-full border-2 border-[#181818]/70 px-6 py-3 text-sm font-bold text-[#181818] transition hover:bg-white/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Kenali YOLOO Merch</a>
            </div>
        </div>
        <div class="relative mx-auto w-full max-w-[28rem]">
            <div aria-hidden="true" class="absolute -right-2 top-8 h-20 w-20 rotate-12 rounded-3xl bg-[#E8C050] sm:-right-5 sm:h-24 sm:w-24"></div>
            <div aria-hidden="true" class="absolute -bottom-2 -left-2 h-16 w-16 -rotate-12 rounded-full bg-[#58C0C8] sm:-left-5 sm:h-20 sm:w-20"></div>
            <div class="relative rounded-[2rem] border-4 border-[#181818] bg-[#F8F8F8] p-4 shadow-[8px_8px_0_#181818] sm:p-6">
                <img src="{{ asset('images/brand/yoloo-mascot.png') }}" alt="Maskot YOLOO Merch" width="1200" height="1200" fetchpriority="high" class="mx-auto max-h-[26rem] w-full object-contain">
            </div>
        </div>
    </div>
</section>
