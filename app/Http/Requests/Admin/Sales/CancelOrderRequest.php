<?php

namespace App\Http\Requests\Admin\Sales;

use Illuminate\Foundation\Http\FormRequest;

/** PRD-05 FIN | FIN-FR-016 - Pembatalan transaksi (Owner). Alasan wajib. */
class CancelOrderRequest extends FormRequest
{
    protected $errorBag = 'cancel';

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
            'reason.required' => 'Alasan pembatalan wajib diisi.',
            'reason.max' => 'Alasan maksimal 500 karakter.',
        ];
    }
}
