{{--
    PAGE-FIN-002 - Section 'status-history' (Detail Order)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Variabel dari OrderController::show: $statusHistory (terbaru di atas).
              Histori bersifat append-only (FIN-BR-007).
    Status  : diimplementasikan (FIN-FR-010).
--}}
<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="text-base font-semibold text-gray-900">Riwayat Status</h2>

    @if ($statusHistory->isEmpty())
        <p class="mt-2 text-sm text-gray-500">Belum ada riwayat perubahan status.</p>
    @else
        <div class="mt-3 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="py-2 pr-4">Waktu</th>
                        <th class="py-2 pr-4">Perubahan</th>
                        <th class="py-2 pr-4">Oleh</th>
                        <th class="py-2">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($statusHistory as $history)
                        <tr>
                            <td class="whitespace-nowrap py-2 pr-4 text-gray-700">{{ $history->changed_at?->format('d/m/Y H:i') }}</td>
                            <td class="py-2 pr-4 text-gray-900">
                                {{ $history->previous_status?->label() ?? 'Dibuat' }}
                                &rarr;
                                <span class="font-medium">{{ $history->new_status->label() }}</span>
                            </td>
                            <td class="py-2 pr-4 text-gray-700">{{ $history->changedBy?->name ?? 'Sistem' }}</td>
                            <td class="py-2 text-gray-700">{{ $history->note ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
