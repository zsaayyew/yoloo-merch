{{-- CAT-FR-005 / CAT-FR-006 · Opsi produk · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
@php
    $cartOptions = request()->query('opsi');
    $cartQuantity = filter_var(request()->query('jumlah'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $previousOptions = collect(old('option_value_ids', is_array($cartOptions) ? $cartOptions : []))
        ->filter(fn ($id) => is_scalar($id))->map(fn ($id) => (string) $id);
    $previousQuantity = old('quantity', $cartQuantity ?: $minimumQuantity);
@endphp

<form id="konfigurasi" method="POST" action="{{ route('products.estimate', ['product' => $product->slug]) }}"
      data-catalog-configurator data-price-model="{{ $priceModel }}" class="space-y-5 rounded-2xl bg-zinc-100 p-5">
    @csrf
    <input name="product_id" value="{{ $product->id }}" type="hidden">
    <h2 class="text-xl font-bold">Konfigurasi produk</h2>

    @if ($errors->any())
        <ul role="alert" class="list-disc space-y-1 rounded-lg bg-red-50 p-3 pl-8 text-sm text-red-900">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($groups as $group)
            @php($field = 'option_group_' . $group->id)
            <div>
                <label for="{{ $field }}" class="mb-1 block text-sm font-semibold">
                    {{ $group->name }}{{ $group->is_required ? ' (wajib)' : '' }}
                </label>
                <select id="{{ $field }}" name="option_value_ids[]" data-catalog-input="{{ $field }}"
                        aria-describedby="{{ $field }}_error" @if ($errors->has($field)) aria-invalid="true" @endif
                        @required($group->is_required) class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2">
                    <option value="">{{ $group->is_required ? 'Pilih opsi' : 'Tanpa pilihan' }}</option>
                    @foreach ($values->get($group->id, collect()) as $option)
                        <option value="{{ $option->id }}" @selected($previousOptions->containsStrict((string) $option->id))>
                            {{ $option->label }}
                        </option>
                    @endforeach
                </select>
                <p id="{{ $field }}_error" class="mt-1 text-sm text-red-800"
                   data-catalog-field-error="{{ $field }}" aria-live="polite">{{ $errors->first($field) }}</p>
            </div>
        @endforeach

        <div>
            <label class="mb-1 block text-sm font-semibold" for="catalog-quantity">Jumlah (pcs)</label>
            <input id="catalog-quantity" name="quantity" type="number" inputmode="numeric" required step="1"
                   min="{{ $minimumQuantity }}" value="{{ is_scalar($previousQuantity) ? $previousQuantity : '' }}"
                   data-catalog-input="quantity" aria-describedby="catalog-quantity-hint catalog-quantity-error"
                   @if ($errors->has('quantity')) aria-invalid="true" @endif
                   class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2">
            <p id="catalog-quantity-hint" class="mt-1 text-sm text-zinc-700">Minimum {{ $minimumQuantity }} pcs.</p>
            <p id="catalog-quantity-error" class="mt-1 text-sm text-red-800"
               data-catalog-field-error="quantity" aria-live="polite">{{ $errors->first('quantity') }}</p>
        </div>
    </div>

    <p data-catalog-selection hidden class="text-sm text-zinc-700"></p>
    <p data-catalog-status role="status" class="text-sm text-zinc-700"></p>
    <button type="button" data-catalog-retry hidden class="text-sm font-semibold underline">Coba lagi</button>

    @includeWhen($priceModel === 'calculator', 'public.catalog.sections.calculator-panel')
    @include('public.catalog.sections.order-actions')
</form>
