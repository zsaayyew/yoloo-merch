<?php

namespace App\Http\Requests\PublicSite;

use App\Models\OrderAttachment;
use Illuminate\Foundation\Http\FormRequest;

/**
 * PRD-03 ORD — Validasi unggah lampiran (ORD-FR-011, ORD-BR-010).
 * "mimetypes" memeriksa jenis file dari ISI file, bukan dari ekstensi,
 * jadi file .exe yang diganti nama menjadi .jpg tetap ditolak.
 */
class UploadAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:' . OrderAttachment::MAX_SIZE_KB,
                'mimetypes:' . implode(',', OrderAttachment::ALLOWED_MIMES),
                'extensions:jpg,jpeg,png,pdf',
            ],
            'caption' => ['nullable', 'string', 'max:200'],
            'cart_item_id' => ['nullable', 'integer'], // diisi untuk lampiran desain per item checkout
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Pilih file terlebih dahulu.',
            'file.file' => 'File gagal diunggah. Coba lagi.',
            'file.uploaded' => 'File gagal diunggah. Pastikan ukurannya tidak lebih dari 10 MB.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
            'file.mimetypes' => 'Jenis file tidak didukung. Gunakan JPG, PNG, atau PDF.',
            'file.extensions' => 'Jenis file tidak didukung. Gunakan JPG, PNG, atau PDF.',
            'caption.max' => 'Keterangan file maksimal 200 karakter.',
        ];
    }
}
