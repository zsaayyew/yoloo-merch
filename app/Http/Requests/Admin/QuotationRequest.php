<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/** PRD-05 FIN - Validasi penawaran. Nominal Rupiah bulat, tanpa desimal. */
class QuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        // "1.500.000" atau "Rp 1.500.000" => "1500000"
        $this->merge([
            'quotation_amount' => preg_replace('/\D+/', '', (string) $this->input('quotation_amount', '')),
        ]);
    }

    public function rules(): array
    {
        return [
            'quotation_amount' => ['required', 'integer', 'min:1', 'max:9999999999'],
            'quotation_terms' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'quotation_amount' => 'nominal penawaran',
            'quotation_terms' => 'syarat penawaran',
        ];
    }

    public function messages(): array
    {
        return [
            'quotation_amount.required' => 'Nominal penawaran wajib diisi.',
            'quotation_amount.integer' => 'Nominal penawaran harus berupa angka.',
            'quotation_amount.min' => 'Nominal penawaran harus lebih dari 0.',
        ];
    }
}
