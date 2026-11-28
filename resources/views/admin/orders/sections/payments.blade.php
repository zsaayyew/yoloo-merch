{{--
    PAGE-FIN-002 / PAGE-FIN-005 - Section 'payments' (Detail Order)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : Variabel dari OrderController::show: $order, $ledger, $ledgerError, $payments,
              $isOwner, $canRecordPayment. Angka berasal dari PaymentLedgerService, bukan dihitung di view.
              Void hanya Owner. Payment tidak pernah dihapus (FIN-BR-009).
    Status  : diimplementasikan (FIN-FR-008, FIN-FR-009).
--}}
@php $rp = fn (int $amount): string => 'Rp'.number_format($amount, 0, ',', '.'); @endphp
<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="text-base font-semibold text-gray-900">Pembayaran</h2>

    @if ($ledgerError)
        <p class="mt-3 rounded-lg bg-amber-50 px-4 py-2 text-sm text-amber-900" role="alert">
            Angka pembayaran tidak dapat dihitung karena data tidak konsisten: {{ $ledgerError }}
        </p>
    @endif

    @if ($ledger)
        <dl class="mt-3 grid gap-3 text-sm sm:grid-cols-2 lg:grid-cols-5">
            <div><dt class="text-gray-500">Total tagihan</dt><dd class="font-medium text-gray-900">{{ $rp($ledger['total_bill']) }}</dd></div>
            <div><dt class="text-gray-500">Pembayaran valid</dt><dd class="font-medium text-gray-900">{{ $rp($ledger['valid_payments_total']) }}</dd></div>
            <div><dt class="text-gray-500">Refund selesai</dt><dd class="font-medium text-gray-900">{{ $rp($ledger['completed_refunds_total']) }}</dd></div>
            <div><dt class="text-gray-500">Pembayaran bersih</dt><dd class="font-medium text-gray-900">{{ $rp($ledger['net_payment']) }}</dd></div>
            <div><dt class="text-gray-500">Sisa tagihan</dt><dd class="font-semibold text-gray-900">{{ $rp($ledger['remaining']) }}</dd></div>
        </dl>
        <p class="mt-2 text-sm text-gray-600">
            Status pembayaran: <span class="font-medium text-gray-900">{{ $ledger['payment_status']->label() }}</span>
            &middot; {{ $ledger['refund_status_label'] }}
        </p>
    @endif

    @if ($errors->payment->any())
        <ul class="mt-3 list-disc space-y-1 rounded-lg bg-red-50 px-8 py-2 text-sm text-red-800" role="alert">
            @foreach ($errors->payment->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif
    @if ($errors->void->any())
        <ul class="mt-3 list-disc space-y-1 rounded-lg bg-red-50 px-8 py-2 text-sm text-red-800" role="alert">
            @foreach ($errors->void->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif

    @if ($payments->isEmpty())
        <p class="mt-3 text-sm text-gray-500">Belum ada pembayaran yang dicatat.</p>
    @else
        <div class="mt-3 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4 text-right">Nominal</th>
                        <th class="py-2 pr-4">Referensi</th>
                        <th class="py-2 pr-4">Keterangan</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 pr-4">Dicatat oleh</th>
                        <th class="py-2"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($payments as $payment)
                        <tr class="align-top {{ $payment->isValid() ? '' : 'text-gray-400' }}">
                            <td class="whitespace-nowrap py-2 pr-4">{{ $payment->payment_date?->format('d/m/Y') }}</td>
                            <td class="whitespace-nowrap py-2 pr-4 text-right">{{ $rp((int) $payment->amount) }}</td>
                            <td class="py-2 pr-4">{{ $payment->reference ?: '-' }}</td>
                            <td class="py-2 pr-4">{{ $payment->note ?: '-' }}</td>
                            <td class="py-2 pr-4">
                                {{ $payment->status->label() }}
                                @unless ($payment->isValid())
                                    <span class="block text-xs">Alasan: {{ $payment->void_reason }}</span>
                                    <span class="block text-xs">{{ $payment->voided_at?->format('d/m/Y H:i') }} oleh {{ $payment->voidedBy?->name ?? '-' }}</span>
                                @endunless
                            </td>
                            <td class="py-2 pr-4">{{ $payment->recordedBy?->name ?? '-' }}</td>
                            <td class="py-2">
                                @if ($isOwner && $payment->isValid())
                                    @include('admin.orders.modals.void-payment', ['payment' => $payment])
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($canRecordPayment)
        @include('admin.orders.modals.payment-form')
    @elseif ($order->status === \App\Enums\OrderStatus::Cancelled)
        <p class="mt-3 text-sm text-gray-500">Transaksi dibatalkan, pembayaran baru tidak dapat dicatat.</p>
    @elseif ($ledger && $ledger['remaining'] <= 0)
        <p class="mt-3 text-sm text-gray-500">Transaksi ini sudah lunas dan tidak memiliki sisa tagihan.</p>
    @endif
</section>
