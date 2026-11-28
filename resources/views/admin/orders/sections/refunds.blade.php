{{--
    PAGE-FIN-005 - Section 'refunds' (Detail Order)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Variabel dari OrderController::show: $refunds, $payments, $refundable, $isOwner.
              Refund hanya dicatat Owner dan terhubung ke payment sumber. Sistem tidak mengirim uang.
    Status  : diimplementasikan (FIN-FR-019).
--}}
@php $rp = fn (int $amount): string => 'Rp'.number_format($amount, 0, ',', '.'); @endphp
<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="text-base font-semibold text-gray-900">Refund</h2>

    @if ($errors->refund->any())
        <ul class="mt-3 list-disc space-y-1 rounded-lg bg-red-50 px-8 py-2 text-sm text-red-800" role="alert">
            @foreach ($errors->refund->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif

    @if ($refunds->isEmpty())
        <p class="mt-3 text-sm text-gray-500">Belum ada refund yang dicatat.</p>
    @else
        <div class="mt-3 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">Dari pembayaran</th>
                        <th class="py-2 pr-4 text-right">Nominal</th>
                        <th class="py-2 pr-4">Metode</th>
                        <th class="py-2 pr-4">Alasan</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2">Dicatat oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($refunds as $refund)
                        <tr class="align-top">
                            <td class="whitespace-nowrap py-2 pr-4">{{ $refund->refund_date?->format('d/m/Y') }}</td>
                            <td class="py-2 pr-4">
                                {{ $refund->payment?->payment_date?->format('d/m/Y') }}
                                ({{ $rp((int) ($refund->payment?->amount ?? 0)) }})
                            </td>
                            <td class="whitespace-nowrap py-2 pr-4 text-right">{{ $rp((int) $refund->amount) }}</td>
                            <td class="py-2 pr-4">{{ $refund->method }}@if ($refund->reference) <span class="block text-xs text-gray-500">{{ $refund->reference }}</span>@endif</td>
                            <td class="py-2 pr-4">{{ $refund->reason }}</td>
                            <td class="py-2 pr-4">{{ $refund->status->label() }}</td>
                            <td class="py-2">{{ $refund->recordedBy?->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($isOwner)
        @foreach ($payments as $payment)
            @if (($refundable[$payment->id] ?? 0) > 0)
                @include('admin.orders.modals.refund-form', ['payment' => $payment, 'max' => $refundable[$payment->id]])
            @endif
        @endforeach
    @else
        <p class="mt-3 text-sm text-gray-500">Refund hanya dapat dicatat oleh Owner.</p>
    @endif
</section>
