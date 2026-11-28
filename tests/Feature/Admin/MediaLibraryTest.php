<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Role;
use App\Models\SiteContent;
use App\Models\User;
use App\Services\Content\MediaLibrary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaLibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_can_be_uploaded_updated_and_deleted(): void
    {
        Storage::fake('public');

        $user = $this->makeUser();

        $this->actingAs($user)
            ->post(route('admin.media.store'), [
                'files' => [
                    UploadedFile::fake()->image('brosur.jpg', 800, 600)->size(300),
                    UploadedFile::fake()->create('katalog.pdf', 120, 'application/pdf'),
                ],
            ])
            ->assertRedirect(route('admin.media.index'))
            ->assertSessionHas('status');

        $this->assertSame(2, Media::query()->count());

        $media = Media::query()->where('original_name', 'brosur.jpg')->firstOrFail();
        $this->assertTrue($media->isImage());
        $this->assertSame($user->id, $media->uploaded_by);
        Storage::disk('public')->assertExists($media->path);

        $this->actingAs($user)
            ->patch(route('admin.media.update', $media), ['alt_text' => 'Brosur YOLOO Merch'])
            ->assertRedirect(route('admin.media.index'));

        $this->assertSame('Brosur YOLOO Merch', $media->fresh()->alt_text);

        $this->actingAs($user)
            ->delete(route('admin.media.destroy', $media))
            ->assertRedirect(route('admin.media.index'));

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($media->path);
    }

    public function test_media_rejects_unsupported_file_types(): void
    {
        Storage::fake('public');

        $this->actingAs($this->makeUser())
            ->post(route('admin.media.store'), [
                'files' => [UploadedFile::fake()->create('skrip.exe', 10)],
            ])
            ->assertSessionHasErrors('files.0');

        $this->assertSame(0, Media::query()->count());
    }

    public function test_form_uploads_are_recorded_in_the_media_library_with_their_usage(): void
    {
        $this->withoutVite();
        Storage::fake('public');

        $user = $this->makeUser();
        $category = Category::query()->create(['name' => 'Kaos', 'slug' => 'kaos', 'is_active' => true]);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Kaos Custom',
            'slug' => 'kaos-custom',
            'price_model' => 'quotation',
            'status' => Product::STATUS_DRAFT,
        ]);

        $this->actingAs($user)
            ->put(route('admin.products.update', $product), [
                'category_id' => $category->id,
                'name' => 'Kaos Custom',
                'price_model' => 'quotation',
                'status' => Product::STATUS_DRAFT,
                'images' => [UploadedFile::fake()->image('kaos.png', 400, 400)],
            ])
            ->assertRedirect(route('admin.products.edit', $product));

        $image = $product->images()->firstOrFail();
        $productMedia = Media::query()->where('path', $image->path)->firstOrFail();
        $this->assertSame('Kaos Custom', $productMedia->alt_text);
        $this->assertDatabaseHas('media_usages', [
            'media_id' => $productMedia->id,
            'usable_type' => ProductImage::class,
            'usable_id' => $image->id,
            'field' => 'image',
        ]);

        $galleryCategory = GalleryCategory::query()->create([
            'name' => 'Merch Kampus',
            'slug' => 'merch-kampus',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('admin.content.gallery.store'), [
                'gallery_category_id' => $galleryCategory->id,
                'title' => 'Jaket Komunitas',
                'is_published' => '1',
                'image' => UploadedFile::fake()->image('jaket.jpg', 600, 400),
            ])
            ->assertRedirect(route('admin.content.gallery.index'));

        $item = GalleryItem::query()->firstOrFail();
        $galleryMedia = Media::query()->where('path', $item->image_path)->firstOrFail();
        $this->assertSame('Jaket Komunitas', $galleryMedia->alt_text);
        $this->assertDatabaseHas('media_usages', [
            'media_id' => $galleryMedia->id,
            'usable_type' => GalleryItem::class,
            'usable_id' => $item->id,
            'field' => 'image',
        ]);

        $this->actingAs($user)
            ->put(route('admin.content.homepage.update'), [
                'sections' => [
                    'hero' => [
                        'title' => 'Merch Kustom',
                        'is_published' => '1',
                        'image' => UploadedFile::fake()->image('hero.jpg', 1200, 600),
                    ],
                ],
            ])
            ->assertRedirect(route('admin.content.homepage.edit'));

        $hero = SiteContent::forPage(SiteContent::PAGE_HOME)->get('hero');
        $this->assertNotNull($hero);
        $contentMedia = Media::query()->where('path', $hero->image_path)->firstOrFail();
        $this->assertSame('Merch Kustom', $contentMedia->alt_text);
        $this->assertDatabaseHas('media_usages', [
            'media_id' => $contentMedia->id,
            'usable_type' => SiteContent::class,
            'usable_id' => $hero->id,
            'field' => 'image',
        ]);

        $this->assertSame(3, Media::query()->count());

        $this->get(route('admin.media.index'))
            ->assertOk()
            ->assertSee('Produk: Kaos Custom')
            ->assertSee('Galeri: Jaket Komunitas')
            ->assertSee('Konten Beranda');
    }

    public function test_media_file_can_be_replaced_without_losing_its_usage(): void
    {
        Storage::fake('public');

        $media = Media::query()->create([
            'path' => UploadedFile::fake()->image('lama.jpg')->store('media', 'public'),
            'original_name' => 'lama.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
        ]);
        $oldPath = $media->path;

        $item = GalleryItem::query()->create(['title' => 'Jaket Komunitas', 'is_published' => true]);
        app(MediaLibrary::class)->attach($item, 'image', $media);

        $this->actingAs($this->makeUser())
            ->put(route('admin.media.replace', $media), [
                'file' => UploadedFile::fake()->image('baru.jpg', 500, 500)->size(400),
            ])
            ->assertRedirect(route('admin.media.index'))
            ->assertSessionHas('status');

        $media->refresh();
        $this->assertNotSame($oldPath, $media->path);
        $this->assertSame('baru.jpg', $media->original_name);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($media->path);

        $this->assertSame(1, $media->usages()->count());
        $this->assertDatabaseHas('media_usages', [
            'media_id' => $media->id,
            'usable_type' => GalleryItem::class,
            'usable_id' => $item->id,
            'field' => 'image',
        ]);
    }

    public function test_removing_a_product_image_also_removes_its_media_entry(): void
    {
        Storage::fake('public');

        $user = $this->makeUser();
        $category = Category::query()->create(['name' => 'Kaos', 'slug' => 'kaos', 'is_active' => true]);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Kaos Custom',
            'slug' => 'kaos-custom',
            'price_model' => 'quotation',
            'status' => Product::STATUS_DRAFT,
        ]);

        $this->actingAs($user)
            ->put(route('admin.products.update', $product), [
                'category_id' => $category->id,
                'name' => 'Kaos Custom',
                'price_model' => 'quotation',
                'status' => Product::STATUS_DRAFT,
                'images' => [UploadedFile::fake()->image('kaos.png', 400, 400)],
            ])
            ->assertRedirect(route('admin.products.edit', $product));

        $image = $product->images()->firstOrFail();
        $media = Media::query()->where('path', $image->path)->firstOrFail();

        $this->actingAs($user)
            ->delete(route('admin.products.images.destroy', [$product, $image]))
            ->assertRedirect(route('admin.products.edit', $product));

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($media->path);
    }

    private function makeUser(): User
    {
        $role = Role::firstOrCreate(['name' => Role::ADMIN], ['label' => 'Admin']);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }
}
