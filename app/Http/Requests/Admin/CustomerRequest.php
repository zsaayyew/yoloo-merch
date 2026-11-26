<?php

namespace App\Http\Requests\Admin;

use App\Support\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * PRD-05 FIN - Validasi form pelanggan (FIN-FR-011, FIN-BR-012).
 * Nomor WhatsApp dinormalisasi ke 62xxxxxxxxxx sebelum divalidasi dan wajib unik.
 */
class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'whatsapp_number' => PhoneNumber::normalize((string) $this->input('whatsapp_number', '')),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        $customer = $this->route('customer');

        return [
            'name' => ['required', 'string', 'max:100'],
            'whatsapp_number' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! PhoneNumber::isValid((string) $value)) {
                        $fail('Nomor WhatsApp tidak valid. Contoh: 0812-3456-7890.');
                    }
                },
                Rule::unique('customers', 'whatsapp_number')->ignore($customer?->id),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'whatsapp_number' => 'nomor WhatsApp',
            'email' => 'email',
            'notes' => 'catatan',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama pelanggan wajib diisi.',
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp_number.unique' => 'Nomor WhatsApp ini sudah terdaftar sebagai pelanggan.',
            'email.email' => 'Format email tidak valid.',
        ];
    }
}
