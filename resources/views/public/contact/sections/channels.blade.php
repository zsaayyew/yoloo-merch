{{-- PAGE-CP-005: kanal hanya ditampilkan bila tersedia dari konfigurasi resmi terverifikasi. --}}
<section class="bg-[#E87830] text-[#181818]">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 sm:py-20 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
        <div><p class="text-sm font-extrabold uppercase tracking-[0.16em]">Kami siap mendengar</p><h1 id="contact-title" class="mt-3 text-4xl font-black tracking-tight sm:text-5xl">Hubungi YOLOO Merch</h1><p class="mt-5 max-w-lg leading-7 text-[#181818]/75">Pilih kanal komunikasi yang paling nyaman untuk pertanyaan tentang produk dan layanan.</p></div>
        <div class="grid gap-3 sm:grid-cols-2">
            @if (!empty($contactChannels['whatsapp_number']))
                <a id="whatsapp-contact" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactChannels['whatsapp_number']) }}" rel="noopener noreferrer" class="group rounded-2xl border-2 border-[#181818]/15 bg-white p-5 motion-safe:transition motion-safe:hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#14747b]">WhatsApp</span><span class="mt-2 block text-lg font-black text-[#181818]">{{ $contactChannels['whatsapp_display'] ?? $contactChannels['whatsapp_number'] }}</span><span class="mt-2 block text-sm font-semibold text-[#181818]/65">Mulai percakapan <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span></span>
                </a>
            @endif
            @if (!empty($contactChannels['instagram_url']) && !empty($contactChannels['instagram_handle']))
                <a href="{{ $contactChannels['instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="group rounded-2xl border-2 border-[#181818]/15 bg-white p-5 motion-safe:transition motion-safe:hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#c75d1d]">Instagram</span><span class="mt-2 block text-lg font-black text-[#181818]">{{ $contactChannels['instagram_handle'] }}</span><span class="mt-2 block text-sm font-semibold text-[#181818]/65">Kunjungi profil <span aria-hidden="true">↗</span></span>
                </a>
            @endif
            @if (!empty($contactChannels['email']))
                <a href="mailto:{{ $contactChannels['email'] }}" class="group rounded-2xl border-2 border-[#181818]/15 bg-white p-5 motion-safe:transition motion-safe:hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b] sm:col-span-2">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#c75d1d]">Email</span><span class="mt-2 block break-all text-lg font-black text-[#181818]">{{ $contactChannels['email'] }}</span><span class="mt-2 block text-sm font-semibold text-[#181818]/65">Kirim email <span aria-hidden="true">→</span></span>
                </a>
            @endif
            @if (empty($contactChannels['whatsapp_number']) && empty($contactChannels['instagram_url']) && empty($contactChannels['email']))
                <p class="rounded-2xl border-2 border-[#181818]/15 bg-white/75 p-6 leading-7 text-[#181818]/75 sm:col-span-2">Informasi kontak akan ditampilkan setelah kanal resmi dikonfirmasi.</p>
            @endif
        </div>
    </div>
</section>
