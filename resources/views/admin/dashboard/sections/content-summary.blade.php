{{--
    PAGE-ADM-002 - Section 'content-summary' (Dashboard)
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Partial khusus halaman ini; di-include dari file halaman utama.
              Menerima $cards dari DashboardController (ADM-FR-002): label, value, note, route.
--}}
<section>
    <div class="flex flex-wrap items-baseline justify-between gap-2">
        <h2 class="text-base font-semibold text-gray-900">Ringkasan Data</h2>
        <p class="text-xs text-gray-500">Angka diambil langsung dari database</p>
    </div>

    <div class="mt-3 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <a href="{{ route($card['route']) }}"
               class="rounded-lg border border-gray-200 bg-white p-4 transition hover:border-gray-300 hover:shadow-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                <p class="text-sm text-gray-600">{{ $card['label'] }}</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ number_format($card['value'], 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-gray-500">{{ $card['note'] }}</p>
            </a>
        @endforeach
    </div>
</section>
