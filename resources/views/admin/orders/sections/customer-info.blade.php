{{--
    PAGE-FIN-002 - Section 'customer-info' (Detail Order / Request)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Status  : diimplementasikan.
--}}
<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Pelanggan</h2>

    @if ($customer)
        <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <dt class="text-gray-500">Nama</dt>
                <dd class="font-medium text-gray-900">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-700 hover:underline">{{ $customer->name }}</a>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">WhatsApp</dt>
                <dd class="font-medium text-gray-900">
                    <a href="https://wa.me/{{ $customer->whatsapp_number }}" target="_blank" rel="noopener" class="text-blue-700 hover:underline">{{ $customer->whatsapp_number }}</a>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Email</dt>
                <dd class="font-medium text-gray-900">{{ $customer->email ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Channel</dt>
                <dd class="font-medium text-gray-900">{{ ($order ?? $orderRequest)->channel->label() }}</dd>
            </div>
        </dl>
    @else
        <p class="text-sm text-gray-500">Data pelanggan tidak ditemukan.</p>
    @endif
</section>
