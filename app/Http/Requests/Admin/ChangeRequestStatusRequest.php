<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * PRD-05 FIN - Validasi ubah status request.
 * "Penawaran Dikirim" lewat form penawaran dan "Dikonversi" lewat konversi, jadi tidak ada di sini.
 * Aturan transisi sebenarnya dijaga StatusTransitionService.
 */
class ChangeRequestStatusRequest extends FormRequest
{
    public const MANUAL_STATUSES = ['under_review', 'awaiting_info', 'approved', 'rejected', 'expired'];

    /** Status yang wajib disertai alasan (FLOW-FIN-001). */
    public const REASON_REQUIRED = ['rejected', 'expired'];

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(self::MANUAL_STATUSES)],
            'reason' => [
                Rule::requiredIf(fn () => in_array($this->input('status'), self::REASON_REQUIRED, true)),
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function attributes(): array
    {
        return ['status' => 'status tujuan', 'reason' => 'alasan'];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Pilih status tujuan.',
            'status.in' => 'Status tujuan tidak valid.',
            'reason.required' => 'Alasan wajib diisi untuk status Ditolak dan Kedaluwarsa.',
        ];
    }
}
