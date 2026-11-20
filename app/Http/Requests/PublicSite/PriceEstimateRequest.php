<?php

namespace App\Http\Requests\PublicSite;

use Illuminate\Foundation\Http\FormRequest;

class PriceEstimateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $ids = $this->input('option_value_ids');

        if (is_array($ids)) {
            $this->merge(['option_value_ids' => array_values(array_filter(
                $ids,
                fn ($id) => $id !== null && $id !== '',
            ))]);
        }
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1'],
            'option_value_ids' => ['sometimes', 'array'],
            'option_value_ids.*' => ['required', 'integer', 'min:1', 'distinct'],
            'intent' => ['sometimes', 'in:check,estimate,quotation'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Isi jumlah pesanan.',
            'quantity.integer' => 'Jumlah harus berupa bilangan bulat.',
            'quantity.min' => 'Jumlah minimal 1 pcs.',
            'option_value_ids.*' => 'Pilihan opsi tidak valid.',
            'option_value_ids.array' => 'Pilihan opsi tidak valid.',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return route('products.show', ['product' => $this->route('product')]).'#konfigurasi';
    }
}