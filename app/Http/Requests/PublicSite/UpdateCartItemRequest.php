<?php

namespace App\Http\Requests\PublicSite;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PRD-03 ORD — Validasi ubah jumlah item keranjang (ORD-FR-002).
 * Minimum order per produk dicek ulang oleh CAT di CartService::updateQuantity().
 */
class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:100000'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka bulat.',
            'quantity.min' => 'Jumlah minimal 1. Gunakan tombol Hapus untuk mengeluarkan produk.',
            'quantity.max' => 'Jumlah terlalu besar. Silakan ajukan custom order.',
        ];
    }
}
