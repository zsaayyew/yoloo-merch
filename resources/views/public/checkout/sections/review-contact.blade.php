{{--
    Section : Data kontak untuk ditinjau - PAGE-ORD-003
    Domain  : PRD-03 ORD (Raisyah)
    Status  : diimplementasikan.
--}}
<div class="rounded-xl border border-gray-200 p-5">
    <div class="flex items-start justify-between gap-4">
        <h2 class="font-semibold text-gray-900">Data kontak</h2>
        <a href="{{ route('checkout.customer') }}" class="text-sm font-medium text-gray-700 underline hover:text-gray-900">Ubah</a>
    </div>

    <dl class="mt-3 grid gap-3 text-sm sm:grid-cols-2">
        <div>
            <dt class="text-gray-500">Nama</dt>
            <dd class="font-medium text-gray-900">{{ $customer['name'] }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">WhatsApp</dt>
            <dd class="font-medium text-gray-900">+{{ $customer['whatsapp_number'] }}</dd>
        </div>
        <div>
            <dt class="text-gray-500">Email</dt>
            <dd class="font-medium text-gray-900">{{ $customer['email'] ?? '' ?: '-' }}</dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-gray-500">Catatan</dt>
            <dd class="whitespace-pre-line font-medium text-gray-900">{{ $customer['notes'] ?? '' ?: '-' }}</dd>
        </div>
    </dl>
</div>
