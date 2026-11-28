<?php

namespace Tests\Feature\Admin;

use App\Models\Collaboration;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\Role;
use App\Models\SiteContent;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_admin_content_pages(): void
    {
        $this->get(route('admin.content.homepage.edit'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.content.testimonials.index'))->assertRedirect(route('admin.login'));
    }

    public function test_homepage_content_can_be_saved(): void
    {
        $this->actingAs($this->makeUser(Role::ADMIN))
            ->put(route('admin.content.homepage.update'), [
                'sections' => [
                    'hero' => [
                        'title' => 'Judul Hero Baru',
                        'subtitle' => 'Subjudul hero',
                        'body' => 'Isi hero',
                        'is_published' => '1',
                    ],
                    'intro' => [
                        'title' => 'Perkenalan',
                        'is_published' => '0',
                    ],
                ],
            ])
            ->assertRedirect(route('admin.content.homepage.edit'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('site_contents', [
            'page' => SiteContent::PAGE_HOME,
            'section' => 'hero',
            'title' => 'Judul Hero Baru',
            'is_published' => true,
        ]);

        $this->assertDatabaseHas('site_contents', [
            'page' => SiteContent::PAGE_HOME,
            'section' => 'intro',
            'is_published' => false,
        ]);
    }

    public function test_about_content_can_be_saved(): void
    {
        $this->actingAs($this->makeUser(Role::ADMIN))
            ->put(route('admin.content.about.update'), [
                'sections' => [
                    'profile' => [
                        'title' => 'Profil YOLOO',
                        'body' => 'Cerita usaha',
                        'is_published' => '1',
                    ],
                ],
            ])
            ->assertRedirect(route('admin.content.about.edit'));

        $this->assertDatabaseHas('site_contents', [
            'page' => SiteContent::PAGE_ABOUT,
            'section' => 'profile',
            'title' => 'Profil YOLOO',
        ]);
    }

    public function test_business_profile_can_be_saved(): void
    {
        $this->actingAs($this->makeUser(Role::ADMIN))
            ->put(route('admin.content.settings.update'), [
                'settings' => [
                    'business_name' => 'YOLOO Merch',
                    'tagline' => 'Merchandise custom',
                    'email' => 'halo@yoloo.test',
                    'instagram' => '@yoloo',
                ],
            ])
            ->assertRedirect(route('admin.content.settings.edit'));

        $this->assertSame('YOLOO Merch', SiteSetting::value('business_name'));
        $this->assertSame('halo@yoloo.test', SiteSetting::value('email'));
    }

    public function test_testimonial_crud_and_publication_toggle(): void
    {
        $user = $this->makeUser(Role::ADMIN);

        $this->actingAs($user)
            ->post(route('admin.content.testimonials.store'), [
                'customer_name' => 'Rani',
                'customer_role' => 'Ketua BEM',
                'rating' => 5,
                'quote' => 'Hasil sablon rapi dan cepat.',
                'is_published' => '1',
                'sort_order' => 2,
            ])
            ->assertRedirect(route('admin.content.testimonials.index'));

        $testimonial = Testimonial::query()->firstOrFail();
        $this->assertTrue($testimonial->is_published);

        $this->actingAs($user)
            ->patch(route('admin.content.testimonials.publish', $testimonial))
            ->assertRedirect(route('admin.content.testimonials.index'));

        $this->assertFalse($testimonial->fresh()->is_published);

        $this->actingAs($user)
            ->put(route('admin.content.testimonials.update', $testimonial), [
                'customer_name' => 'Rani Putri',
                'rating' => 4,
                'quote' => 'Pelayanan ramah.',
            ])
            ->assertRedirect(route('admin.content.testimonials.index'));

        $this->assertSame('Rani Putri', $testimonial->fresh()->customer_name);
        $this->assertFalse($testimonial->fresh()->is_published);

        $this->actingAs($user)
            ->delete(route('admin.content.testimonials.destroy', $testimonial))
            ->assertRedirect(route('admin.content.testimonials.index'));

        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }

    public function test_collaboration_can_be_created_and_hidden(): void
    {
        $user = $this->makeUser(Role::ADMIN);

        $this->actingAs($user)
            ->post(route('admin.content.collaborations.store'), [
                'partner_name' => 'Kopi Senja',
                'description' => 'Kolaborasi merch komunitas.',
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.content.collaborations.index'));

        $collaboration = Collaboration::query()->firstOrFail();
        $this->assertTrue($collaboration->is_published);

        $this->actingAs($user)
            ->patch(route('admin.content.collaborations.publish', $collaboration))
            ->assertRedirect(route('admin.content.collaborations.index'));

        $this->assertFalse($collaboration->fresh()->is_published);
    }

    public function test_gallery_category_and_item_can_be_managed(): void
    {
        $user = $this->makeUser(Role::ADMIN);

        $this->actingAs($user)
            ->post(route('admin.content.gallery-categories.store'), [
                'name' => 'Merch Kampus',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.content.gallery-categories.index'));

        $category = GalleryCategory::query()->firstOrFail();
        $this->assertSame('merch-kampus', $category->slug);
        $this->assertTrue($category->is_active);

        $this->actingAs($user)
            ->post(route('admin.content.gallery.store'), [
                'gallery_category_id' => $category->id,
                'title' => 'Jaket Komunitas',
                'caption' => 'Hasil produksi batch pertama.',
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.content.gallery.index'));

        $item = GalleryItem::query()->firstOrFail();
        $this->assertSame($category->id, $item->gallery_category_id);
        $this->assertTrue($item->is_published);

        $this->actingAs($user)
            ->delete(route('admin.content.gallery-categories.destroy', $category))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseHas('gallery_categories', ['id' => $category->id]);
    }

    public function test_duplicate_gallery_category_name_is_rejected(): void
    {
        $user = $this->makeUser(Role::ADMIN);
        $category = GalleryCategory::query()->create([
            'name' => 'Merch Kampus',
            'slug' => 'merch-kampus',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('admin.content.gallery-categories.store'), [
                'name' => 'merch kampus',
                'is_active' => '1',
            ])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('gallery_categories', 1);

        $this->actingAs($user)
            ->put(route('admin.content.gallery-categories.update', $category), [
                'name' => 'Merch Kampus',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.content.gallery-categories.index'));

        $this->assertSame('merch-kampus', $category->fresh()->slug);
    }

    private function makeUser(string $roleName): User
    {
        $role = Role::firstOrCreate(['name' => $roleName], ['label' => ucfirst($roleName)]);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }
}
