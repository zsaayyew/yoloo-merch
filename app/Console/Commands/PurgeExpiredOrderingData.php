<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\OrderAttachment;
use App\Models\SubmissionToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * PRD-03 ORD — Pembersihan data pemesanan sementara (ORD-BR-010, ORD-FR-003, ORD-FR-013).
 *
 *   - Lampiran berstatus temporary yang lebih dari 24 jam: file + barisnya dihapus.
 *   - Keranjang yang sudah lewat expires_at (7 hari): dihapus beserta itemnya.
 *   - Token kiriman yang kedaluwarsa dan tidak pernah dipakai: dihapus.
 *     Token yang sudah dipakai (tertaut ke order/request) disimpan sampai 30 hari.
 *
 * Tidak pernah menyentuh order, order_items, order_requests, atau lampiran yang sudah dikirim.
 * Dijadwalkan di routes/console.php. Jalankan manual: php artisan ordering:purge-expired
 */
class PurgeExpiredOrderingData extends Command
{
    protected $signature = 'ordering:purge-expired {--dry-run : Tampilkan jumlah data tanpa menghapus}';

    protected $description = 'Hapus keranjang, token, dan file lampiran sementara yang sudah kedaluwarsa';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $attachments = OrderAttachment::query()->expiredTemporary();
        $carts = Cart::query()->expired();
        $tokens = SubmissionToken::query()->where(function ($query) {
            $query->where(fn ($q) => $q->whereNull('used_at')->where('expires_at', '<', now()))
                ->orWhere(fn ($q) => $q->whereNotNull('used_at')->where('used_at', '<', now()->subDays(30)));
        });

        if ($dryRun) {
            $this->table(['Data', 'Akan dihapus'], [
                ['Lampiran sementara > 24 jam', $attachments->count()],
                ['Keranjang kedaluwarsa', $carts->count()],
                ['Token kiriman kedaluwarsa', $tokens->count()],
            ]);

            return self::SUCCESS;
        }

        // 1. File lampiran sementara (hapus file fisik dulu, lalu barisnya).
        $deletedFiles = 0;
        $attachments->chunkById(100, function ($chunk) use (&$deletedFiles) {
            foreach ($chunk as $attachment) {
                Storage::disk(OrderAttachment::DISK)->delete($attachment->stored_path);
                $attachment->delete();
                $deletedFiles++;
            }
        });

        // 2. Keranjang kedaluwarsa. Lampiran item yang tersisa ikut dibersihkan dulu.
        $deletedCarts = 0;
        $carts->chunkById(100, function ($chunk) use (&$deletedCarts) {
            foreach ($chunk as $cart) {
                $itemIds = $cart->items()->pluck('id');

                OrderAttachment::query()->temporary()->whereIn('cart_item_id', $itemIds)->get()
                    ->each(function (OrderAttachment $attachment) {
                        Storage::disk(OrderAttachment::DISK)->delete($attachment->stored_path);
                        $attachment->delete();
                    });

                $cart->items()->delete();
                $cart->delete();
                $deletedCarts++;
            }
        });

        // 3. Token kiriman.
        $deletedTokens = $tokens->delete();

        $this->info("Selesai: {$deletedFiles} lampiran, {$deletedCarts} keranjang, {$deletedTokens} token dihapus.");

        return self::SUCCESS;
    }
}
