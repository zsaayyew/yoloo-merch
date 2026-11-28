{{-- CAT-FR-005 / CAT-FR-006 · Opsi produk · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
@php
    $cartOptions = request()->query('opsi');
    $cartQuantity = filter_var(request()->query('jumlah'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $previousOptions = collect(old('option_value_ids', is_array($cartOptions) ? $cartOptions : []))
        ->filter(fn ($id) => is_scalar($id))->map(fn ($id) => (string) $id);
    $previousQuantity = old('quantity', $cartQuantity ?: $minimumQuantity);
@endphp

<form id="konfigurasi" method="POST" action="{{ route('products.estimate', ['product' => $product->slug]) }}"
      data-catalog-configurator data-price-model="{{ $priceModel }}" class="min-w-0 scroll-mt-24 space-y-5 rounded-3xl border border-[#cbd7d2] bg-white p-4 sm:p-6">
    @csrf
    <input name="product_id" value="{{ $product->id }}" type="hidden">
    <h2 class="border-b border-[#dde4df] pb-4 text-2xl font-black tracking-tight">Konfigurasi produk</h2>

    @if ($errors->any())
        <ul role="alert" data-catalog-server-errors data-pricing-only="{{ $errors->keys() === ['pricing'] ? 'true' : 'false' }}"
            class="list-disc space-y-1 rounded-xl border-l-4 border-[#E97E37] bg-[#fff2e7] p-3 pl-8 text-sm text-[#793617]">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="grid min-w-0 gap-4 sm:grid-cols-2">
        @foreach ($groups as $group)
            @php($field = 'option_group_' . $group->id)
            <div class="min-w-0">
                <label for="{{ $field }}" class="mb-2 flex flex-wrap items-baseline gap-x-2 text-sm font-bold">
                    {{ $group->name }}
                    @if ($group->is_required)
                        <span class="text-xs font-medium text-[#80411c]">Wajib</span>
                    @endif
                </label>
                <select id="{{ $field }}" name="option_value_ids[]" data-catalog-input="{{ $field }}"
                        aria-describedby="{{ $field }}_error" @if ($errors->has($field)) aria-invalid="true" @endif
                        @required($group->is_required) class="block min-h-11 w-full min-w-0 rounded-xl border border-[#95aaa7] bg-white px-3 py-2 text-base text-[#182F32] focus:border-[#18767c] aria-invalid:border-red-700">
                    <option value="">{{ $group->is_required ? 'Pilih opsi' : 'Tanpa pilihan' }}</option>
                    @foreach ($values->get($group->id, collect()) as $option)
                        <option value="{{ $option->id }}" @selected($previousOptions->containsStrict((string) $option->id))>
                            {{ $option->label }}
                        </option>
                    @endforeach
                </select>
                <p id="{{ $field }}_error" class="mt-1.5 text-sm text-red-800 empty:mt-0"
                   data-catalog-field-error="{{ $field }}" aria-live="polite">{{ $errors->first($field) }}</p>
            </div>
        @endforeach

        <div class="min-w-0 sm:col-span-full">
            <label class="mb-2 block text-sm font-bold" for="catalog-quantity">Jumlah (pcs)</label>
            <input id="catalog-quantity" name="quantity" type="number" inputmode="numeric" required step="1"
                   min="{{ $minimumQuantity }}" value="{{ is_scalar($previousQuantity) ? $previousQuantity : '' }}"
                   data-catalog-input="quantity" aria-describedby="catalog-quantity-hint catalog-quantity-error"
                   @if ($errors->has('quantity')) aria-invalid="true" @endif
                   class="block min-h-11 w-full max-w-48 rounded-xl border border-[#95aaa7] bg-white px-3 py-2 text-base font-bold text-[#182F32] focus:border-[#18767c] aria-invalid:border-red-700">
            <p id="catalog-quantity-hint" class="mt-1.5 text-sm text-[#465b5d]">Minimum {{ $minimumQuantity }} pcs.</p>
            <p id="catalog-quantity-error" class="mt-1 text-sm text-red-800"
               data-catalog-field-error="quantity" aria-live="polite">{{ $errors->first('quantity') }}</p>
        </div>
    </div>

    <p data-catalog-selection hidden aria-label="Pilihan saat ini" class="rounded-xl bg-[#e9f5f3] px-4 py-3 text-sm font-semibold text-[#234f50]"></p>
    <p data-catalog-status role="status" class="text-sm text-[#465b5d] empty:hidden"></p>
    <button type="button" data-catalog-retry hidden class="min-h-11 rounded-full border border-[#254b4e] px-4 text-sm font-bold text-[#254b4e] hover:bg-[#e9f5f3]">Coba lagi</button>

    @includeWhen($priceModel === 'calculator', 'public.catalog.sections.calculator-panel')
    @include('public.catalog.sections.order-actions')
</form>
