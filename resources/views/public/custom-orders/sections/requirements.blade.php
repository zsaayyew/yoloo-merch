{{--
    Section : Kebutuhan pesanan - PAGE-ORD-005
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : Deskripsi 20-2.000 karakter, jumlah positif, tenggat tidak di masa lalu (ORD-FR-012).
    Status  : diimplementasikan.
--}}
<fieldset class="space-y-5">
    <legend class="text-lg font-semibold text-gray-900">Kebutuhan pesanan</legend>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">
            Deskripsi kebutuhan <span class="text-red-600">*</span>
        </label>
        <textarea id="description" name="description" rows="5" required minlength="20" maxlength="2000"
            placeholder="Contoh: stiker logo cafe bentuk lingkaran, warna hitam-kuning, untuk ditempel di cup minuman."
            class="{{ $inputClass }} @error('description') border-red-500 @else border-gray-300 @enderror">{{ old('description') }}</textarea>
        <p class="mt-1 text-xs text-gray-500">Minimal 20 karakter, maksimal 2.000 karakter.</p>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700">
                Jumlah <span class="text-red-600">*</span>
            </label>
            <input type="number" id="quantity" name="quantity" min="1" step="1" required
                value="{{ old('quantity', $prefillQuantity ?? '') }}" placeholder="Contoh: 100"
                class="{{ $inputClass }} @error('quantity') border-red-500 @else border-gray-300 @enderror">
            @error('quantity')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="desired_deadline" class="block text-sm font-medium text-gray-700">Tenggat yang diinginkan</label>
            <input type="date" id="desired_deadline" name="desired_deadline"
                min="{{ now()->toDateString() }}" value="{{ old('desired_deadline') }}"
                class="{{ $inputClass }} @error('desired_deadline') border-red-500 @else border-gray-300 @enderror">
            @error('desired_deadline')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="specification" class="block text-sm font-medium text-gray-700">Spesifikasi</label>
        <textarea id="specification" name="specification" rows="3" maxlength="1000"
            placeholder="Ukuran, bahan, warna, finishing, atau detail lain yang sudah kamu tahu."
            class="{{ $inputClass }} @error('specification') border-red-500 @else border-gray-300 @enderror">{{ old('specification') }}</textarea>
        @error('specification')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="budget" class="block text-sm font-medium text-gray-700">Perkiraan anggaran (Rp)</label>
            <input type="text" id="budget" name="budget" inputmode="numeric"
                value="{{ old('budget') }}" placeholder="Contoh: 500000"
                class="{{ $inputClass }} @error('budget') border-red-500 @else border-gray-300 @enderror">
            <p class="mt-1 text-xs text-gray-500">Opsional. Membantu admin menyesuaikan penawaran.</p>
            @error('budget')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan tambahan</label>
            <input type="text" id="notes" name="notes" maxlength="500"
                value="{{ old('notes') }}" placeholder="Opsional"
                class="{{ $inputClass }} @error('notes') border-red-500 @else border-gray-300 @enderror">
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</fieldset>
