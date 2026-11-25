{{--
    Section : Formulir data pelanggan - PAGE-ORD-002
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Nama 2-100, WhatsApp 10-15 digit (dinormalisasi 62...), email opsional, catatan <= 500.
              Berisi juga lampiran desain per item; tombol lanjut dikunci selama file masih diunggah.
    Status  : diimplementasikan.
--}}
@php
    $inputClass = 'mt-1 block w-full rounded-lg border bg-white px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/20';
@endphp

<form method="POST" action="{{ route('checkout.customer.store') }}" data-submit-guard class="space-y-5 rounded-xl border border-gray-200 p-5">
    @csrf

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama lengkap <span class="text-red-600">*</span></label>
        <input type="text" id="name" name="name" required minlength="2" maxlength="100" autocomplete="name"
            value="{{ old('name', $customer['name'] ?? '') }}"
            class="{{ $inputClass }} @error('name') border-red-500 @else border-gray-300 @enderror">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">Nomor WhatsApp <span class="text-red-600">*</span></label>
            <input type="tel" id="whatsapp_number" name="whatsapp_number" required autocomplete="tel"
                value="{{ old('whatsapp_number', $customer['whatsapp_number'] ?? '') }}" placeholder="081234567890"
                class="{{ $inputClass }} @error('whatsapp_number') border-red-500 @else border-gray-300 @enderror">
            @error('whatsapp_number')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" maxlength="255" autocomplete="email"
                value="{{ old('email', $customer['email'] ?? '') }}" placeholder="Opsional"
                class="{{ $inputClass }} @error('email') border-red-500 @else border-gray-300 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan untuk admin</label>
        <textarea id="notes" name="notes" rows="3" maxlength="500" placeholder="Opsional, maksimal 500 karakter"
            class="{{ $inputClass }} @error('notes') border-red-500 @else border-gray-300 @enderror">{{ old('notes', $customer['notes'] ?? '') }}</textarea>
        @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="border-t border-gray-100 pt-5">
        @include('public.checkout.sections.item-attachments')
    </div>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ route('cart.index') }}" class="text-center text-sm font-medium text-gray-600 hover:text-gray-900">Kembali ke keranjang</a>
        <button type="submit" data-loading-text="Memproses..."
            class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-6 py-3 font-semibold text-white hover:bg-gray-700 disabled:opacity-60">
            Lanjut ke Ringkasan
        </button>
    </div>
</form>
