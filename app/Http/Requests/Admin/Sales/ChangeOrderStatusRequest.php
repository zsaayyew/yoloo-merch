<?php

namespace App\Http\Requests\Admin\Sales;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** PRD-05 FIN | FIN-FR-010 - Ubah status pengerjaan order. Aturan transisi ada di StatusTransitionService. */
class ChangeOrderStatusRequest extends FormRequest
{
    protected $errorBag = 'status';

    /** Akses dijaga Gate di controller. */
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(OrderStatus::class)],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'status.required' => 'Pilih status tujuan.',
            'status.enum' => 'Status tujuan tidak dikenal.',
            'note.max' => 'Catatan maksimal 500 karakter.',
        ];
    }
}
