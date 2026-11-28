<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PRD-05 FIN | FIN-FR-019 - Catat refund (Owner).
 * Aturan "tidak melebihi saldo payment sumber" ada di RefundService.
 */
class StoreRefundRequest extends FormRequest
{
    protected $errorBag = 'refund';

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1'],
            'refund_date' => ['required', 'date_format:Y-m-d'],
            'method' => ['required', 'string', 'max:50'],
            'reference' => ['nullable', 'string', 'max:100'],
            'reason' => ['required', 'string', 'max:500'],
            'submission_key' => ['nullable', 'string', 'max:64'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'amount.required' => 'Nominal refund wajib diisi.',
            'amount.integer' => 'Nominal refund harus berupa angka Rupiah bulat.',
            'amount.min' => 'Nominal refund harus lebih dari nol.',
            'refund_date.required' => 'Tanggal refund wajib diisi.',
            'refund_date.date_format' => 'Tanggal refund tidak valid.',
            'method.required' => 'Metode pengembalian wajib diisi.',
            'method.max' => 'Metode pengembalian maksimal 50 karakter.',
            'reference.max' => 'Referensi maksimal 100 karakter.',
            'reason.required' => 'Alasan refund wajib diisi.',
            'reason.max' => 'Alasan maksimal 500 karakter.',
        ];
    }
}
