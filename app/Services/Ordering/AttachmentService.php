<?php

namespace App\Services\Ordering;

use App\Enums\AttachmentStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderAttachment;
use App\Models\OrderItem;
use App\Models\OrderRequest;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

/**
 * PRD-03 ORD — Pengelola lampiran pelanggan (ORD-FR-011, ORD-BR-010).
 *
 * Dua jenis lampiran sementara:
 *   - custom order : cart_item_id NULL, ditandai upload token sesi
 *   - item checkout: cart_item_id terisi (lampiran desain per produk di keranjang)
 *
 * Alur:
 *   1. storeTemporary(): disimpan di tmp-uploads/ (disk local, privat), nama acak, status "temporary".
 *   2. Saat dikirim: attachToRequest() / attachToOrderItem() memindahkan file ke
 *      order-attachments/{kode}/ dan mengubah status menjadi "submitted".
 *   3. File sementara yang tidak pernah dikirim dihapus command pembersihan setelah 24 jam.
 */
class AttachmentService
{
    private const SESSION_KEY = 'ordering.upload_token';

    /** Token acak per sesi untuk menandai pemilik file sementara. */
    public function uploadToken(Session $session): string
    {
        $token = $session->get(self::SESSION_KEY);

        if (! is_string($token) || strlen($token) !== 64) {
            $token = Str::random(64);
            $session->put(self::SESSION_KEY, $token);
        }

        return $token;
    }

    /** Lampiran sementara milik sesi ini untuk formulir custom order. */
    public function pendingForCustomOrder(string $uploadToken): Collection
    {
        return $this->temporaryQuery($uploadToken, null)->orderBy('id')->get();
    }

    /**
     * Lampiran sementara per item keranjang, dikelompokkan berdasarkan cart_item_id.
     *
     * @param  array<int>  $cartItemIds
     */
    public function pendingForCartItems(array $cartItemIds): \Illuminate\Support\Collection
    {
        if ($cartItemIds === []) {
            return collect();
        }

        return OrderAttachment::query()
            ->temporary()
            ->whereIn('cart_item_id', $cartItemIds)
            ->orderBy('id')
            ->get()
            ->groupBy('cart_item_id');
    }

    /**
     * Simpan file ke folder sementara. Jika $cartItem diisi, file menjadi lampiran item keranjang.
     *
     * @throws ValidationException jika jumlah file sudah mencapai batas
     */
    public function storeTemporary(UploadedFile $file, string $uploadToken, ?string $caption = null, ?CartItem $cartItem = null): OrderAttachment
    {
        if ($this->temporaryQuery($uploadToken, $cartItem?->id)->count() >= OrderAttachment::MAX_FILES) {
            throw ValidationException::withMessages([
                'file' => 'Maksimal ' . OrderAttachment::MAX_FILES . ' file. Hapus salah satu file untuk menambah yang baru.',
            ]);
        }

        // store() memberi nama acak (hash) sehingga nama asli tidak dipakai di server.
        $path = $file->store(OrderAttachment::TEMP_DIR, OrderAttachment::DISK);

        if ($path === false) {
            throw new RuntimeException('Gagal menyimpan file lampiran.');
        }

        return OrderAttachment::create([
            'cart_item_id' => $cartItem?->id,
            'original_name' => Str::limit($file->getClientOriginalName(), 250, ''),
            'stored_path' => $path,
            'mime_type' => $file->getMimeType(),   // dibaca dari isi file
            'size_bytes' => $file->getSize(),
            'caption' => $caption,
            'status' => AttachmentStatus::Temporary,
            'session_token' => $uploadToken,
        ]);
    }

    /** Hanya pemilik sesi yang boleh menghapus file sementaranya sendiri. */
    public function canDelete(OrderAttachment $attachment, string $uploadToken): bool
    {
        return $attachment->status === AttachmentStatus::Temporary
            && hash_equals((string) $attachment->session_token, $uploadToken);
    }

    public function deleteTemporary(OrderAttachment $attachment): void
    {
        Storage::disk(OrderAttachment::DISK)->delete($attachment->stored_path);
        $attachment->delete();
    }

    /** Hapus semua lampiran sementara milik satu item keranjang (item dihapus dari keranjang). */
    public function deleteForCartItem(CartItem $cartItem): void
    {
        OrderAttachment::query()
            ->temporary()
            ->where('cart_item_id', $cartItem->id)
            ->get()
            ->each(fn (OrderAttachment $attachment) => $this->deleteTemporary($attachment));
    }

    /** Pindahkan lampiran ke item lain (dipakai saat "Ubah opsi" mengganti item keranjang). */
    public function moveToCartItem(CartItem $from, CartItem $to): void
    {
        OrderAttachment::query()
            ->temporary()
            ->where('cart_item_id', $from->id)
            ->update(['cart_item_id' => $to->id]);
    }

    /**
     * Pindahkan lampiran custom order milik sesi ke request yang baru disimpan.
     * Dipanggil di dalam DB::transaction() CustomRequestSubmissionService.
     */
    public function attachToRequest(OrderRequest $orderRequest, string $uploadToken): int
    {
        $attachments = $this->temporaryQuery($uploadToken, null)->get();

        foreach ($attachments as $attachment) {
            $this->finalize($attachment, $orderRequest->code, ['order_request_id' => $orderRequest->id]);
        }

        return $attachments->count();
    }

    /**
     * Pindahkan lampiran item keranjang ke item order yang baru dibuat.
     * Dipanggil di dalam DB::transaction() OrderSubmissionService.
     */
    public function attachToOrderItem(Order $order, OrderItem $orderItem, int $cartItemId): int
    {
        $attachments = OrderAttachment::query()->temporary()->where('cart_item_id', $cartItemId)->get();

        foreach ($attachments as $attachment) {
            $this->finalize($attachment, $order->code, [
                'order_id' => $order->id,
                'order_item_id' => $orderItem->id,
                'cart_item_id' => null,
            ]);
        }

        return $attachments->count();
    }

    private function finalize(OrderAttachment $attachment, string $folder, array $links): void
    {
        $disk = Storage::disk(OrderAttachment::DISK);
        $newPath = OrderAttachment::FINAL_DIR . '/' . $folder . '/' . basename($attachment->stored_path);

        if ($disk->exists($attachment->stored_path) && ! $disk->move($attachment->stored_path, $newPath)) {
            throw new RuntimeException('Gagal memindahkan file lampiran.');
        }

        $attachment->update($links + [
            'stored_path' => $newPath,
            'status' => AttachmentStatus::Submitted,
            'submitted_at' => now(),
        ]);
    }

    /** Lampiran sementara milik sesi: custom order ($cartItemId null) atau satu item keranjang. */
    private function temporaryQuery(string $uploadToken, ?int $cartItemId): Builder
    {
        $query = OrderAttachment::query()
            ->temporary()
            ->whereNull('order_request_id')
            ->whereNull('order_id');

        // Lampiran item keranjang: pemiliknya ditentukan keranjang (cookie), bukan sesi.
        if ($cartItemId) {
            return $query->where('cart_item_id', $cartItemId);
        }

        return $query->where('session_token', $uploadToken)->whereNull('cart_item_id');
    }
}
