<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Media;
use App\Models\MediaUsage;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SiteContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaBackfillTest extends TestCase
{
    use RefreshDatabase;

    public function test_backfill_registers_existing_images_with_their_usage(): void
    {
        Storage::fake('public');

        [$productImage, $content, $item, $missingImage] = $this->makeLegacyRecords();

        $this->artisan('media:backfill')->assertSuccessful();

        $this->assertSame(3, Media::query()->count());

        $productMedia = Media::query()->where('path', $productImage->path)->firstOrFail();
        $this->assertSame('Kaos Custom', $productMedia->alt_text);
        $this->assertGreaterThan(0, $productMedia->size);
        $this->assertDatabaseHas('media_usages', [
            'media_id' => $productMedia->id,
            'usable_type' => ProductImage::class,
            'usable_id' => $productImage->id,
            'field' => 'image',
        ]);

        $content->refresh();
        $contentMedia = Media::query()->where('path', $content->image_path)->firstOrFail();
        $this->assertSame('Merch Kustom', $contentMedia->alt_text);
        $this->assertDatabaseHas('media_usages', [
            'media_id' => $contentMedia->id,
            'usable_type' => SiteContent::class,
            'usable_id' => $content->id,
            'field' => 'image',
        ]);

        $item->refresh();
        $galleryMedia = Media::query()->where('path', $item->image_path)->firstOrFail();
        $this->assertSame('Jaket Komunitas', $galleryMedia->alt_text);
        $this->assertDatabaseHas('media_usages', [
            'media_id' => $galleryMedia->id,
            'usable_type' => GalleryItem::class,
            'usable_id' => $item->id,
            'field' => 'image',
        ]);

        $this->assertDatabaseMissing('media', ['path' => $missingImage->path]);
        $this->assertSame(3, MediaUsage::query()->count());
    }

    public function test_backfill_can_be_dry_run_and_is_idempotent(): void
    {
        Storage::fake('public');

        $this->makeLegacyRecords();

        $this->artisan('media:backfill', ['--dry-run' => true])->assertSuccessful();

        $this->assertSame(0, Media::query()->count());
        $this->assertSame(0, MediaUsage::query()->count());

        $this->artisan('media:backfill')->assertSuccessful();
        $this->artisan('media:backfill')->assertSuccessful();

        $this->assertSame(3, Media::query()->count());
        $this->assertSame(3, MediaUsage::query()->count());
    }

    /**
     * Buat data lama beserta berkasnya seperti kondisi sebelum pustaka media ada.
     *
     * @return array{0: ProductImage, 1: SiteContent, 2: GalleryItem, 3: ProductImage}
     */
    private function makeLegacyRecords(): array
    {
        $category = Category::query()->create(['name' => 'Kaos', 'slug' => 'kaos', 'is_active' => true]);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Kaos Custom',
            'slug' => 'kaos-custom',
            'price_model' => 'quotation',
            'status' => Product::STATUS_DRAFT,
        ]);

        Storage::disk('public')->put('products/kaos-lama.png', 'gambar-produk-lama');
        Storage::disk('public')->put('content/home/hero-lama.png', 'gambar-konten-lama');
        Storage::disk('public')->put('gallery/jaket-lama.jpg', 'foto-galeri-lama');

        $productImage = $product->images()->create([
            'path' => 'products/kaos-lama.png',
            'alt_text' => 'Kaos Custom',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        $missingImage = $product->images()->create([
            'path' => 'products/hilang.png',
            'alt_text' => 'Gambar hilang',
            'is_primary' => false,
            'sort_order' => 2,
        ]);

        $content = SiteContent::query()->create([
            'page' => SiteContent::PAGE_HOME,
            'section' => 'hero',
            'title' => 'Merch Kustom',
            'image_path' => 'content/home/hero-lama.png',
            'is_published' => true,
        ]);

        $item = GalleryItem::query()->create([
            'title' => 'Jaket Komunitas',
            'image_path' => 'gallery/jaket-lama.jpg',
            'is_published' => true,
        ]);

        return [$productImage, $content, $item, $missingImage];
    }
}
