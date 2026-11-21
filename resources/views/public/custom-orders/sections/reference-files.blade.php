{{--
    Section : Unggah file referensi - PAGE-ORD-005
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : ORD-FR-011. JPG/PNG/PDF, maks 10 MB per file, maks 5 file.
              Diunggah satu per satu lewat resources/js/public/file-upload.js ke route attachments.store.
              Input file sengaja tanpa atribut name, karena file tidak ikut terkirim bersama formulir.
    Status  : diimplementasikan.
--}}
@php
    $maxFiles = \App\Models\OrderAttachment::MAX_FILES;
    $maxSizeKb = \App\Models\OrderAttachment::MAX_SIZE_KB;
    $allowedMimes = \App\Models\OrderAttachment::ALLOWED_MIMES;
@endphp

<fieldset class="space-y-3"
    data-file-upload
    data-upload-url="{{ route('attachments.store') }}"
    data-max-files="{{ $maxFiles }}"
    data-max-bytes="{{ $maxSizeKb * 1024 }}"
    data-allowed-types="{{ implode(',', $allowedMimes) }}">
    <legend class="text-lg font-semibold text-gray-900">File referensi</legend>
    <p class="text-sm text-gray-600">
        Opsional. Lampirkan contoh desain, logo, atau sketsa. Format JPG, PNG, atau PDF,
        maksimal 10 MB per file, paling banyak {{ $maxFiles }} file.
    </p>

    <label for="reference_file"
        class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 px-4 py-6 text-center hover:border-gray-500">
        <span class="font-medium text-gray-900">Pilih file</span>
        <span class="mt-1 text-xs text-gray-500"><span data-file-count>{{ $attachments->count() }}</span> dari {{ $maxFiles }} file</span>
        <input type="file" id="reference_file" class="sr-only" multiple
            accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf">
    </label>

    <p class="hidden text-sm text-red-600" data-file-error role="alert"></p>

    <ul class="space-y-2" data-file-list>
        @foreach ($attachments as $attachment)
            <li class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 px-3 py-2 text-sm" data-attachment-id="{{ $attachment->id }}">
                <span class="min-w-0">
                    <span class="block truncate font-medium text-gray-900">{{ $attachment->original_name }}</span>
                    <span class="text-xs text-gray-500">{{ $attachment->sizeForHumans() }}</span>
                </span>
                <button type="button" data-delete-url="{{ route('attachments.destroy', $attachment) }}"
                    class="shrink-0 text-sm font-medium text-red-600 hover:text-red-800">
                    Hapus
                </button>
            </li>
        @endforeach
    </ul>
</fieldset>
