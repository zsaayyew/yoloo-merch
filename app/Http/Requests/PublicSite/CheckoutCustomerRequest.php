<?php

namespace App\Http\Requests\PublicSite;

use App\Support\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

/**
 * PRD-03 ORD — Validasi data pelanggan checkout (ORD-FR-005, ORD-BR-009).
 * Tanpa akun dan tanpa field alamat/pengiriman (pickup saja, ORD-FR-006).
 */
class CheckoutCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->filled('whatsapp_number')) {
            $merge['whatsapp_number'] = PhoneNumber::normalize((string) $this->input('whatsapp_number'));
        }

        foreach (['name', 'email', 'notes'] as $field) {
            if (is_string($this->input($field))) {
                $merge[$field] = preg_replace('/\s+/u', ' ', trim($this->input($field)));
            }
        }

        $this->merge($merge);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'whatsapp_number' => ['required', 'string', 'regex:/^62\d{8,13}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 2 karakter.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp_number.regex' => 'Nomor WhatsApp tidak valid. Gunakan 10–15 digit, contoh: 081234567890.',
            'email.email' => 'Format email tidak valid. Contoh: nama@email.com.',
            'email.max' => 'Email maksimal 255 karakter.',
            'notes.max' => 'Catatan maksimal 500 karakter.',
        ];
    }
}
