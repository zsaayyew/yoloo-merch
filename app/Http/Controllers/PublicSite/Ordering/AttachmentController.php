<?php

namespace App\Http\Controllers\PublicSite\Ordering;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicSite\UploadAttachmentRequest;
use App\Models\OrderAttachment;
use App\Services\Ordering\AttachmentService;
use App\Services\Ordering\CartService;
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
        private readonly CartService $cartService,
    ) {}

    public function store(UploadAttachmentRequest $request): JsonResponse
    {
        $token = $this->attachmentService->uploadToken($request->session());

        // Lampiran per item hanya boleh untuk item di keranjang milik pengunjung ini.
        $cartItem = null;
        if ($request->filled('cart_item_id')) {
            $cartItem = $this->cartService->findOwnedItem($request, (int) $request->validated('cart_item_id'));
            abort_unless($cartItem !== null, 404);
        }

        $attachment = $this->attachmentService->storeTemporary(
            $request->file('file'),
            $token,
            $request->validated('caption'),
            $cartItem,
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

        // Pemilik: sesi yang mengunggah, atau pemilik keranjang untuk lampiran item.
        $ownsCartItem = $attachment->cart_item_id
            && $attachment->cartItem
            && $this->cartService->owns($request, $attachment->cartItem);

        // 404 (bukan 403) supaya tidak membocorkan keberadaan file milik orang lain.
        abort_unless(
            $attachment->status === \App\Enums\AttachmentStatus::Temporary
                && ($ownsCartItem || $this->attachmentService->canDelete($attachment, $token)),
            404
        );

        $this->attachmentService->deleteTemporary($attachment);

        return response()->json(['deleted' => true]);
    }
}
