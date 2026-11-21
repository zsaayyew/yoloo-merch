{{--
    Section : Data kontak pelanggan - PAGE-ORD-005
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Nama 2-100 karakter, WhatsApp 10-15 digit (dinormalisasi ke 62...), email opsional.
              Tidak ada field alamat/pengiriman (pickup saja).
    Status  : diimplementasikan.
--}}
<fieldset class="space-y-5">
    <legend class="text-lg font-semibold text-gray-900">Data kontak</legend>

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">
            Nama lengkap <span class="text-red-600">*</span>
        </label>
        <input type="text" id="name" name="name" required minlength="2" maxlength="100" autocomplete="name"
            value="{{ old('name') }}"
            class="{{ $inputClass }} @error('name') border-red-500 @else border-gray-300 @enderror">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">
                Nomor WhatsApp <span class="text-red-600">*</span>
            </label>
            <input type="tel" id="whatsapp_number" name="whatsapp_number" required autocomplete="tel"
                value="{{ old('whatsapp_number') }}" placeholder="081234567890"
                class="{{ $inputClass }} @error('whatsapp_number') border-red-500 @else border-gray-300 @enderror">
            <p class="mt-1 text-xs text-gray-500">Admin akan menghubungimu lewat nomor ini.</p>
            @error('whatsapp_number')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" maxlength="255" autocomplete="email"
                value="{{ old('email') }}" placeholder="Opsional"
                class="{{ $inputClass }} @error('email') border-red-500 @else border-gray-300 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</fieldset>
