<?php

namespace App\Http\Requests\Admin\Finance;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PRD-05 FIN | FIN-FR-008 - Catat pembayaran QRIS.
 * Aturan "tidak melebihi sisa tagihan" ada di PaymentService, bukan di sini.
 */
class StorePaymentRequest extends FormRequest
{
    protected $errorBag = 'payment';

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1'],
            'payment_date' => ['required', 'date_format:Y-m-d'],
            'reference' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:500'],
            'submission_key' => ['nullable', 'string', 'max:64'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'amount.required' => 'Nominal pembayaran wajib diisi.',
            'amount.integer' => 'Nominal pembayaran harus berupa angka Rupiah bulat.',
            'amount.min' => 'Nominal pembayaran harus lebih dari nol.',
            'payment_date.required' => 'Tanggal bayar wajib diisi.',
            'payment_date.date_format' => 'Tanggal bayar tidak valid.',
            'reference.max' => 'Referensi transaksi maksimal 100 karakter.',
            'note.max' => 'Keterangan maksimal 500 karakter.',
        ];
    }
}
