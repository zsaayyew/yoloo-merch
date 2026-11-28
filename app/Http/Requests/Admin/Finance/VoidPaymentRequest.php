<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

/** PRD-05 FIN | FIN-FR-009 - Void pembayaran (Owner). Alasan wajib. */
class VoidPaymentRequest extends FormRequest
{
    protected $errorBag = 'void';

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:500'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'reason.required' => 'Alasan Void wajib diisi.',
            'reason.max' => 'Alasan maksimal 500 karakter.',
        ];
    }
}
