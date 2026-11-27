<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/** PRD-05 FIN - Validasi konversi request ke order (DP dan catatan pembayaran opsional). */
class ConvertRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $dp = preg_replace('/\D+/', '', (string) $this->input('agreed_dp_amount', ''));

        $this->merge(['agreed_dp_amount' => $dp === '' ? null : $dp]);
    }

    public function rules(): array
    {
        return [
            'agreed_dp_amount' => ['nullable', 'integer', 'min:0', 'max:9999999999'],
            'payment_terms_note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'agreed_dp_amount' => 'nominal DP',
            'payment_terms_note' => 'catatan pembayaran',
        ];
    }
}
