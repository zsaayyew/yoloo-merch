{{-- PAGE-CP-005: alamat dan jam hanya dirender bila ADM menyediakan informasi terkonfirmasi. --}}
@if (!empty($businessLocation['address']) || !empty($businessLocation['operating_hours']))
    <section aria-labelledby="contact-location-title" class="bg-[#f8f8f8]">
        <div class="mx-auto grid max-w-7xl gap-5 px-4 py-12 sm:px-6 md:grid-cols-2 lg:px-8">
            <div><h2 id="contact-location-title" class="text-xl font-extrabold text-[#181818]">Lokasi</h2>@if (!empty($businessLocation['address']))<p class="mt-2 leading-7 text-[#181818]/70">{{ $businessLocation['address'] }}</p>@endif</div>
            @if (!empty($businessLocation['operating_hours']))<div><h2 class="text-xl font-extrabold text-[#181818]">Jam operasional</h2><p class="mt-2 whitespace-pre-line leading-7 text-[#181818]/70">{{ $businessLocation['operating_hours'] }}</p></div>@endif
        </div>
    </section>
@endif
