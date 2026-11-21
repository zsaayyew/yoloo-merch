{{--
    Component : <x-ordering.pickup-info />
    Domain    : PRD-03 ORD (Raisyah)
    Catatan   : ORD-FR-006. Lokasi & jam pickup dari pengaturan usaha ADM (kunci pickup_location,
                pickup_hours). Jika belum diatur, tidak mengarang alamat. Tidak ada opsi pengiriman.
    Status    : diimplementasikan.
--}}
@inject('orderSubmission', \App\Services\Ordering\OrderSubmissionService::class)
@php($pickup = $orderSubmission->pickupInfo())

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 p-5']) }}>
    <h2 class="font-semibold text-gray-900">Pengambilan pesanan</h2>
    <p class="mt-1 text-sm text-gray-600">
        Pesanan <strong>diambil langsung</strong> di lokasi YOLOO Merch. Tidak tersedia pengiriman.
    </p>

    <dl class="mt-3 space-y-2 text-sm">
        <div>
            <dt class="text-gray-500">Lokasi</dt>
            <dd class="whitespace-pre-line font-medium text-gray-900">{{ $pickup['location'] ?? 'Akan dikonfirmasi admin melalui WhatsApp.' }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Jam pengambilan</dt>
            <dd class="whitespace-pre-line font-medium text-gray-900">{{ $pickup['hours'] ?? 'Akan dikonfirmasi admin melalui WhatsApp.' }}</dd>
        </div>
    </dl>
</div>
