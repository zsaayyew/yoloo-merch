{{--
    Component : <x-form.file-upload :attachments="..." input-id="..." />
    Domain    : SHARED (koordinator), dipakai ORD (custom order & checkout)
    Catatan   : Unggah file satu per satu lewat resources/js/public/file-upload.js ke route attachments.store.
                JPG/PNG/PDF, maks 10 MB per file, maks 5 file. Input file sengaja tanpa atribut name
                karena file tidak ikut terkirim bersama formulir.
    Props     : attachments (koleksi OrderAttachment sementara), inputId, cartItemId (opsional,
                untuk lampiran desain per item checkout), compact (tampilan lebih kecil).
    Status    : diimplementasikan.
--}}
@props(['attachments', 'inputId', 'cartItemId' => null, 'compact' => false])

@php
    $maxFiles = \App\Models\OrderAttachment::MAX_FILES;
    $maxSizeKb = \App\Models\OrderAttachment::MAX_SIZE_KB;
    $allowedMimes = \App\Models\OrderAttachment::ALLOWED_MIMES;
@endphp

<div {{ $attributes->merge(['class' => 'space-y-3']) }}
    data-file-upload
    data-upload-url="{{ route('attachments.store') }}"
    data-max-files="{{ $maxFiles }}"
    data-max-bytes="{{ $maxSizeKb * 1024 }}"
    data-allowed-types="{{ implode(',', $allowedMimes) }}"
    @if ($cartItemId) data-cart-item-id="{{ $cartItemId }}" @endif>

    <label for="{{ $inputId }}" @class([
        'flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 text-center hover:border-gray-500',
        'px-4 py-6' => ! $compact,
        'px-3 py-3' => $compact,
    ])>
        <span class="text-sm font-medium text-gray-900">Pilih file</span>
        <span class="mt-1 text-xs text-gray-500">
            JPG, PNG, atau PDF &middot; maks 10 MB &middot;
            <span data-file-count>{{ $attachments->count() }}</span> dari {{ $maxFiles }} file
        </span>
        <input type="file" id="{{ $inputId }}" class="sr-only" multiple
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
</div>
