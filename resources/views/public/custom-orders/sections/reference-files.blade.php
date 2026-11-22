{{--
    Section : Unggah file referensi - PAGE-ORD-005
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : ORD-FR-011. Memakai komponen <x-form.file-upload>. Lampiran milik custom order (tanpa cart_item_id).
    Status  : diimplementasikan.
--}}
<fieldset class="space-y-3">
    <legend class="text-lg font-semibold text-gray-900">File referensi</legend>
    <p class="text-sm text-gray-600">
        Opsional. Lampirkan contoh desain, logo, atau sketsa. Format JPG, PNG, atau PDF,
        maksimal 10 MB per file, paling banyak {{ \App\Models\OrderAttachment::MAX_FILES }} file.
    </p>

    <x-form.file-upload :attachments="$attachments" input-id="reference_file" />
</fieldset>
