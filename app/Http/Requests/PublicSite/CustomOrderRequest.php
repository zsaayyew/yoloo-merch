<?php

namespace App\Http\Requests\PublicSite;

use App\Support\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

/**
 * PRD-03 ORD — Validasi formulir custom order (ORD-FR-012, ORD-BR-009, ORD-FR-017).
 * Pengunjung guest, jadi authorize() selalu true.
 */
class CustomOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rapikan input sebelum divalidasi:
     * - nomor WhatsApp dinormalisasi ke 62xxxxxxxxxx
     * - anggaran "500.000" menjadi 500000
     */
    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->filled('whatsapp_number')) {
            $merge['whatsapp_number'] = PhoneNumber::normalize((string) $this->input('whatsapp_number'));
        }

        if ($this->filled('budget')) {
            $merge['budget'] = preg_replace('/\D+/', '', (string) $this->input('budget'));
        }

        foreach (['name', 'email', 'description', 'specification', 'notes'] as $field) {
            if (is_string($this->input($field))) {
                $merge[$field] = trim($this->input($field));
            }
        }

        $this->merge($merge);
    }

    public function rules(): array
    {
        return [
            'submission_token' => ['required', 'string', 'size:64'],
            'website' => ['prohibited'], // honeypot: harus kosong
            'product_slug' => ['nullable', 'string', 'max:255'],

            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100000'],
            'specification' => ['nullable', 'string', 'max:1000'],
            'desired_deadline' => ['nullable', 'date', 'after_or_equal:today'],
            'budget' => ['nullable', 'integer', 'min:0', 'max:1000000000'],
            'notes' => ['nullable', 'string', 'max:500'],

            'name' => ['required', 'string', 'min:2', 'max:100'],
            'whatsapp_number' => ['required', 'string', 'regex:/^62\d{8,13}$/'],
            'email' => ['nullable', 'email', 'max:255'],

            'agreement' => ['accepted'],
        ];
    }

    public function attributes(): array
    {
        return [
            'description' => 'deskripsi kebutuhan',
            'quantity' => 'jumlah',
            'specification' => 'spesifikasi',
            'desired_deadline' => 'tenggat yang diinginkan',
            'budget' => 'anggaran',
            'notes' => 'catatan',
            'name' => 'nama',
            'whatsapp_number' => 'nomor WhatsApp',
            'email' => 'email',
            'agreement' => 'persetujuan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':Attribute wajib diisi.',
            'string' => ':Attribute harus berupa teks.',
            'integer' => ':Attribute harus berupa angka bulat.',
            'date' => ':Attribute bukan tanggal yang valid.',
            'email' => 'Format email tidak valid. Contoh: nama@email.com.',
            'min.string' => ':Attribute minimal :min karakter.',
            'max.string' => ':Attribute maksimal :max karakter.',
            'min.numeric' => ':Attribute minimal :min.',
            'max.numeric' => ':Attribute maksimal :max.',
            'quantity.min' => 'Jumlah minimal 1.',
            'desired_deadline.after_or_equal' => 'Tenggat tidak boleh tanggal yang sudah lewat.',
            'whatsapp_number.regex' => 'Nomor WhatsApp tidak valid. Gunakan 10–15 digit, contoh: 081234567890.',
            'agreement.accepted' => 'Centang persetujuan terlebih dahulu sebelum mengirim.',
            'website.prohibited' => 'Pengajuan tidak dapat diproses.',
            'submission_token.*' => 'Formulir sudah kedaluwarsa. Silakan kirim ulang.',
        ];
    }
}
