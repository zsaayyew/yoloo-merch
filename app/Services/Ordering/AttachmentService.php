<?php

namespace App\Services\Ordering;

use App\Enums\AttachmentStatus;
use App\Models\OrderAttachment;
use App\Models\OrderRequest;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

/**
 * PRD-03 ORD — Pengelola lampiran pelanggan (ORD-FR-011, ORD-BR-010).
 *
 * Alur:
 *   1. Pelanggan memilih file -> storeTemporary(): disimpan di tmp-uploads/ (disk local, privat)
 *      dengan nama acak, status "temporary", ditandai upload token milik sesi.
 *   2. Saat formulir dikirim -> attachToRequest(): file dipindah ke order-attachments/{kode}/,
 *      status "submitted", dan ditautkan ke request.
 *   3. File sementara yang tidak pernah dikirim dihapus command pembersihan setelah 24 jam.
 *
 * Catatan: lampiran untuk checkout (per item keranjang) memakai cart_item_id dan akan
 * ditambahkan saat fitur checkout dibuat.
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
        return $this->customOrderQuery($uploadToken)->orderBy('id')->get();
    }

    /**
     * Simpan file ke folder sementara.
     *
     * @throws ValidationException jika jumlah file sudah mencapai batas
     */
    public function storeTemporary(UploadedFile $file, string $uploadToken, ?string $caption = null): OrderAttachment
    {
        if ($this->customOrderQuery($uploadToken)->count() >= OrderAttachment::MAX_FILES) {
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

    /**
     * Pindahkan semua lampiran sementara milik sesi ke request yang baru disimpan.
     * Dipanggil di dalam DB::transaction() CustomRequestSubmissionService.
     *
     * @return int jumlah file yang ditautkan
     */
    public function attachToRequest(OrderRequest $orderRequest, string $uploadToken): int
    {
        $disk = Storage::disk(OrderAttachment::DISK);
        $attachments = $this->customOrderQuery($uploadToken)->get();

        foreach ($attachments as $attachment) {
            $newPath = OrderAttachment::FINAL_DIR . '/' . $orderRequest->code . '/' . basename($attachment->stored_path);

            if ($disk->exists($attachment->stored_path) && ! $disk->move($attachment->stored_path, $newPath)) {
                throw new RuntimeException('Gagal memindahkan file lampiran.');
            }

            $attachment->update([
                'order_request_id' => $orderRequest->id,
                'stored_path' => $newPath,
                'status' => AttachmentStatus::Submitted,
                'submitted_at' => now(),
            ]);
        }

        return $attachments->count();
    }

    private function customOrderQuery(string $uploadToken)
    {
        return OrderAttachment::query()
            ->temporary()
            ->where('session_token', $uploadToken)
            ->whereNull('cart_item_id')
            ->whereNull('order_request_id')
            ->whereNull('order_id');
    }
}
