<?php

namespace App\Console\Commands;

use App\Models\GalleryItem;
use App\Models\Media;
use App\Models\ProductImage;
use App\Models\SiteContent;
use App\Services\Content\MediaLibrary;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

/**
 * PRD-04 ADM — Backfill pustaka media (ADM-FR-008).
 *
 * Gambar produk, gambar section konten, dan foto galeri yang diunggah sebelum
 * fitur pustaka media tersedia belum punya baris media maupun catatan pemakaian.
 * Command ini mencatatnya tanpa memindahkan atau mengubah berkas aslinya, dan
 * aman dijalankan berulang kali. Jalankan manual: php artisan media:backfill
 */
class BackfillMediaLibrary extends Command
{
    protected $signature = 'media:backfill {--dry-run : Tampilkan jumlah data tanpa menyimpan}';

    protected $description = 'Catat gambar produk, konten, dan galeri lama ke pustaka media beserta pemakaiannya';

    public function handle(MediaLibrary $mediaLibrary): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $rows = [];
        $totalCreated = 0;
        $totalLinked = 0;
        $totalMissing = 0;

        foreach ($this->sources() as $label => $sources) {
            $created = 0;
            $linked = 0;
            $missing = 0;

            foreach ($sources as $source) {
                if (! $mediaLibrary->hasStoredFile($source['path'])) {
                    $missing++;
                    $this->warn("Berkas tidak ditemukan, dilewati: {$source['path']}");

                    continue;
                }

                if ($dryRun) {
                    $linked++;

                    if (Media::query()->where('path', $source['path'])->doesntExist()) {
                        $created++;
                    }

                    continue;
                }

                $media = $mediaLibrary->registerStoredFile($source['path'], $source['alt']);

                if ($media === null) {
                    $missing++;

                    continue;
                }

                if ($media->wasRecentlyCreated) {
                    $created++;
                }

                $mediaLibrary->attach($source['owner'], 'image', $media);
                $linked++;
            }

            $rows[] = [$label, count($sources), $created, $linked, $missing];

            $totalCreated += $created;
            $totalLinked += $linked;
            $totalMissing += $missing;
        }

        $this->table(
            ['Sumber', 'Diperiksa', $dryRun ? 'Akan dicatat' : 'Media baru', 'Pemakaian', 'Berkas hilang'],
            $rows,
        );

        $this->info(sprintf(
            '%s%d media baru, %d pemakaian tercatat, %d berkas dilewati.',
            $dryRun ? '[dry-run] ' : '',
            $totalCreated,
            $totalLinked,
            $totalMissing,
        ));

        return self::SUCCESS;
    }

    /**
     * Kumpulkan semua gambar lama beserta pemiliknya.
     *
     * @return array<string, list<array{owner: Model, path: string, alt: string|null}>>
     */
    private function sources(): array
    {
        $sources = ['Gambar produk' => [], 'Gambar konten' => [], 'Foto galeri' => []];

        foreach (ProductImage::query()->with('product')->orderBy('id')->get() as $image) {
            $sources['Gambar produk'][] = [
                'owner' => $image,
                'path' => (string) $image->path,
                'alt' => $image->alt_text ?: $image->product?->name,
            ];
        }

        foreach (SiteContent::query()->whereNotNull('image_path')->orderBy('id')->get() as $content) {
            $sources['Gambar konten'][] = [
                'owner' => $content,
                'path' => (string) $content->image_path,
                'alt' => $content->title ?: $content->label(),
            ];
        }

        foreach (GalleryItem::query()->whereNotNull('image_path')->orderBy('id')->get() as $item) {
            $sources['Foto galeri'][] = [
                'owner' => $item,
                'path' => (string) $item->image_path,
                'alt' => $item->title,
            ];
        }

        return $sources;
    }
}
