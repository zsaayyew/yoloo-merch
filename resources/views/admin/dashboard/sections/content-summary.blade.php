{{--
    PAGE-ADM-002 - Section 'content-summary' (Dashboard)
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Partial khusus halaman ini; di-include dari file halaman utama.
              Menerima $cards dari DashboardController (ADM-FR-002): label, value, note, route.
              Warna mengikuti palet website publik (teal #14747b, ink #181818).
--}}
<section>
    <div class="flex flex-wrap items-end justify-between gap-2">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-[#14747b]">Ringkasan</p>
            <h2 class="mt-1 text-2xl font-black tracking-tight text-[#181818]">Ringkasan Data</h2>
        </div>
        <p class="text-xs font-medium text-[#181818]/50">Angka diambil langsung dari database</p>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <a href="{{ route($card['route']) }}"
               class="rounded-2xl border border-[#181818]/10 bg-white p-5 transition hover:-translate-y-0.5 hover:border-[#14747b] hover:shadow-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                <p class="text-sm font-medium text-[#181818]/60">{{ $card['label'] }}</p>
                <p class="mt-2 text-3xl font-black tracking-tight text-[#181818]">
                    {{ number_format($card['value'], 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs font-medium text-[#14747b]">{{ $card['note'] }}</p>
            </a>
        @endforeach
    </div>
</section>
