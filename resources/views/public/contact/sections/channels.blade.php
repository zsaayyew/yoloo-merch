{{-- PAGE-CP-005: kanal hanya ditampilkan bila tersedia dari konfigurasi resmi terverifikasi. --}}
@php
    $whatsappSource = trim((string) ($contactChannels['whatsapp_number'] ?? ''));
    $whatsappNumber = \App\Support\PhoneNumber::normalize($whatsappSource);
    $whatsappIsValid = $whatsappSource !== '' && \App\Support\PhoneNumber::isValid($whatsappNumber);
    $whatsappDisplay = trim((string) ($contactChannels['whatsapp_display'] ?? '')) ?: $whatsappSource;

    $instagramUrl = trim((string) ($contactChannels['instagram_url'] ?? ''));
    $instagramHandle = trim((string) ($contactChannels['instagram_handle'] ?? ''));
    $instagramHost = parse_url($instagramUrl, PHP_URL_HOST);
    $instagramPath = trim((string) parse_url($instagramUrl, PHP_URL_PATH), '/');
    $instagramProfile = rawurldecode(explode('/', $instagramPath)[0] ?? '');
    $instagramIsValid = filter_var($instagramUrl, FILTER_VALIDATE_URL) !== false
        && strtolower((string) parse_url($instagramUrl, PHP_URL_SCHEME)) === 'https'
        && in_array(strtolower((string) $instagramHost), ['instagram.com', 'www.instagram.com'], true)
        && parse_url($instagramUrl, PHP_URL_PORT) === null
        && parse_url($instagramUrl, PHP_URL_USER) === null
        && parse_url($instagramUrl, PHP_URL_PASS) === null
        && $instagramHandle !== ''
        && strcasecmp($instagramProfile, ltrim($instagramHandle, '@')) === 0;

    $email = trim((string) ($contactChannels['email'] ?? ''));
    $emailIsValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
@endphp
<section class="bg-[#E87830] text-[#181818]">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 sm:py-20 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
        <div><p class="text-sm font-extrabold uppercase tracking-[0.16em]">Informasi resmi</p><h1 id="contact-title" class="mt-3 text-4xl font-black tracking-tight sm:text-5xl">Hubungi YOLOO Merch</h1><p class="mt-5 max-w-lg leading-7 text-[#181818]/75">Kanal resmi untuk pertanyaan tentang produk dan layanan ditampilkan di halaman ini setelah tersedia.</p></div>
        <div class="grid gap-3 sm:grid-cols-2">
            @if ($whatsappIsValid)
                <a id="whatsapp-contact" href="https://wa.me/{{ $whatsappNumber }}" rel="noopener noreferrer" class="group rounded-2xl border-2 border-[#181818]/15 bg-white p-5 motion-safe:transition motion-safe:hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#14747b]">WhatsApp</span><span class="mt-2 block text-lg font-black text-[#181818]">{{ $whatsappDisplay }}</span><span class="mt-2 block text-sm font-semibold text-[#181818]/65">Mulai percakapan <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span></span>
                </a>
            @endif
            @if ($instagramIsValid)
                <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="group rounded-2xl border-2 border-[#181818]/15 bg-white p-5 motion-safe:transition motion-safe:hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#c75d1d]">Instagram</span><span class="mt-2 block text-lg font-black text-[#181818]">{{ $instagramHandle }}</span><span class="mt-2 block text-sm font-semibold text-[#181818]/65">Kunjungi profil <span aria-hidden="true">↗</span></span>
                </a>
            @endif
            @if ($emailIsValid)
                <a href="mailto:{{ $email }}" class="group rounded-2xl border-2 border-[#181818]/15 bg-white p-5 motion-safe:transition motion-safe:hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b] sm:col-span-2">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#c75d1d]">Email</span><span class="mt-2 block break-all text-lg font-black text-[#181818]">{{ $email }}</span><span class="mt-2 block text-sm font-semibold text-[#181818]/65">Kirim email <span aria-hidden="true">→</span></span>
                </a>
            @endif
            @if (!$whatsappIsValid && !$instagramIsValid && !$emailIsValid)
                <p class="rounded-2xl border-2 border-[#181818]/15 bg-white/75 p-6 leading-7 text-[#181818]/75 sm:col-span-2">Informasi kontak resmi belum tersedia. Silakan periksa kembali nanti.</p>
            @endif
        </div>
    </div>
</section>
