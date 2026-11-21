<?php

namespace App\Http\Requests\PublicSite;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PRD-03 ORD — Validasi kirim pesanan (ORD-FR-007, ORD-FR-008, ORD-FR-013).
 * Data pelanggan dan item TIDAK diambil dari form ini, melainkan dari sesi dan keranjang di server.
 */
class SubmitOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'submission_token' => ['required', 'string', 'size:64'],
            'agreement' => ['accepted'],
            'website' => ['prohibited'], // honeypot
        ];
    }

    public function messages(): array
    {
        return [
            'submission_token.*' => 'Formulir sudah kedaluwarsa. Periksa kembali ringkasan, lalu kirim ulang.',
            'agreement.accepted' => 'Centang persetujuan terlebih dahulu sebelum mengirim pesanan.',
            'website.prohibited' => 'Pesanan tidak dapat diproses.',
        ];
    }
}
