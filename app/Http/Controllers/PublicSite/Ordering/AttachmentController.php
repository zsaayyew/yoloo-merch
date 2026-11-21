<?php

namespace App\Http\Controllers\PublicSite\Ordering;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicSite\UploadAttachmentRequest;
use App\Models\OrderAttachment;
use App\Services\Ordering\AttachmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * PRD-03 ORD — Unggah dan hapus lampiran sementara (ORD-FR-011).
 * Dipanggil oleh resources/js/public/file-upload.js lewat fetch, membalas JSON.
 * Route: attachments.store (POST /lampiran), attachments.destroy (DELETE /lampiran/{attachment}).
 */
class AttachmentController extends Controller
{
    public function __construct(
        private readonly AttachmentService $attachmentService,
    ) {}

    public function store(UploadAttachmentRequest $request): JsonResponse
    {
        $token = $this->attachmentService->uploadToken($request->session());

        $attachment = $this->attachmentService->storeTemporary(
            $request->file('file'),
            $token,
            $request->validated('caption'),
        );

        return response()->json([
            'id' => $attachment->id,
            'original_name' => $attachment->original_name,
            'size' => $attachment->sizeForHumans(),
            'is_image' => $attachment->isImage(),
            'delete_url' => route('attachments.destroy', $attachment),
        ], 201);
    }

    public function destroy(Request $request, OrderAttachment $attachment): JsonResponse
    {
        $token = $this->attachmentService->uploadToken($request->session());

        // 404 (bukan 403) supaya tidak membocorkan keberadaan file milik orang lain.
        abort_unless($this->attachmentService->canDelete($attachment, $token), 404);

        $this->attachmentService->deleteTemporary($attachment);

        return response()->json(['deleted' => true]);
    }
}
