<?php

namespace App\Http\Requests\PublicSite;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PRD-03 ORD — Validasi bentuk data "Tambah ke Keranjang" (ORD-FR-001).
 * Validasi aturan produk (opsi wajib, minimum order, kombinasi) dilakukan CAT
 * lewat ConfigurationPayloadBuilder di CartService. Harga tidak diterima dari form.
 *
 * Field yang dikirim dari halaman detail produk (section order-actions milik CAT):
 *   product_id, option_value_ids[] (opsional), quantity,
 *   replace_item_id (opsional, diisi saat pelanggan datang dari tombol "Ubah opsi" di keranjang)
 */
class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Opsi opsional yang tidak dipilih bisa terkirim sebagai string kosong
     * (option_value_ids[]=""). Nilai kosong dibuang supaya tidak dianggap pilihan tidak valid.
     */
    protected function prepareForValidation(): void
    {
        $ids = $this->input('option_value_ids');

        if (is_array($ids)) {
            $this->merge([
                'option_value_ids' => array_values(array_filter($ids, fn ($id) => $id !== null && $id !== '')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'option_value_ids' => ['nullable', 'array', 'max:20'],
            'option_value_ids.*' => ['integer', 'distinct'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100000'],
            'replace_item_id' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk tidak ditemukan.',
            'product_id.exists' => 'Produk tidak ditemukan.',
            'option_value_ids.*.integer' => 'Pilihan opsi tidak valid.',
            'option_value_ids.*.distinct' => 'Pilihan opsi tidak valid.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka bulat.',
            'quantity.min' => 'Jumlah minimal 1.',
            'quantity.max' => 'Jumlah terlalu besar. Silakan ajukan custom order.',
        ];
    }
}
