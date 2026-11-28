<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\OptionGroup;
use App\Models\PricingConfiguration;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatalogManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created_with_generated_slug(): void
    {
        $this->actingAs($this->makeUser(Role::ADMIN))
            ->post(route('admin.categories.store'), [
                'name' => 'Kaos Custom',
                'description' => 'Kaos sablon dan bordir.',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.categories.index'));

        $category = Category::query()->firstOrFail();
        $this->assertSame('kaos-custom', $category->slug);
        $this->assertTrue($category->is_active);
    }

    public function test_category_with_products_is_not_deleted(): void
    {
        $category = Category::query()->create(['name' => 'Kaos', 'slug' => 'kaos', 'is_active' => true]);
        Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Kaos Polos',
            'slug' => 'kaos-polos',
            'price_model' => 'quotation',
            'status' => Product::STATUS_DRAFT,
        ]);

        $this->actingAs($this->makeUser(Role::ADMIN))
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_product_can_be_created_with_specifications_and_archived(): void
    {
        $user = $this->makeUser(Role::ADMIN);
        $category = Category::query()->create(['name' => 'Kaos', 'slug' => 'kaos', 'is_active' => true]);

        $this->actingAs($user)
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'name' => 'Kaos Sablon DTF',
                'description' => 'Kaos sablon full colour.',
                'price_model' => 'calculator',
                'status' => Product::STATUS_DRAFT,
                'allows_direct_purchase' => '1',
                'specifications' => [
                    ['label' => 'Bahan', 'value' => 'Cotton Combed 30s'],
                    ['label' => 'Ukuran', 'value' => 'S-XXL'],
                    ['label' => '', 'value' => 'diabaikan'],
                ],
            ])
            ->assertRedirect();

        $product = Product::query()->firstOrFail();
        $this->assertSame('kaos-sablon-dtf', $product->slug);
        $this->assertTrue($product->allows_direct_purchase);
        $this->assertSame([
            'Bahan' => 'Cotton Combed 30s',
            'Ukuran' => 'S-XXL',
        ], $product->specifications);

        $this->actingAs($user)
            ->patch(route('admin.products.archive', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertSame(Product::STATUS_ARCHIVED, $product->fresh()->status);
    }

    public function test_option_group_and_value_can_be_managed(): void
    {
        $user = $this->makeUser(Role::ADMIN);
        $product = $this->makeProduct();

        $this->actingAs($user)
            ->post(route('admin.products.options.store', $product), [
                'name' => 'Bahan',
                'is_required' => '1',
            ])
            ->assertRedirect(route('admin.products.options.index', $product));

        $group = OptionGroup::query()->firstOrFail();
        $this->assertTrue($group->is_required);

        $this->actingAs($user)
            ->post(route('admin.products.options.values.store', [$product, $group]), [
                'label' => 'Katun Combed 30s',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.products.options.index', $product));

        $value = $group->values()->firstOrFail();
        $this->assertTrue($value->is_active);

        $this->actingAs($user)
            ->patch(route('admin.products.options.values.toggle', [$product, $group, $value]))
            ->assertRedirect(route('admin.products.options.index', $product));

        $this->assertFalse($value->fresh()->is_active);

        $this->actingAs($user)
            ->delete(route('admin.products.options.destroy', [$product, $group]))
            ->assertRedirect(route('admin.products.options.index', $product));

        $this->assertDatabaseMissing('option_groups', ['id' => $group->id]);
    }

    public function test_pricing_version_is_stored_with_calculator_config(): void
    {
        $user = $this->makeUser(Role::ADMIN);
        $product = $this->makeProduct();

        $this->actingAs($user)
            ->post(route('admin.pricing.store', $product), [
                'price_model' => 'calculator',
                'status' => 'active',
                'effective_date' => '2026-11-01',
                'base_price' => 75000,
                'minimum_quantity' => 12,
                'maximum_quantity' => 500,
                'extra_fee' => 5000,
                'minimum_total' => 100000,
                'tiers' => [
                    ['min' => 12, 'max' => 49, 'discount_per_unit' => 0],
                    ['min' => 50, 'max' => 99, 'discount_per_unit' => 2500],
                ],
            ])
            ->assertRedirect(route('admin.pricing.index'));

        $pricing = PricingConfiguration::query()->firstOrFail();

        $this->assertSame(1, $pricing->version);
        $this->assertSame(75000, $pricing->base_price);
        $this->assertSame(12, $pricing->config['minimum_quantity']);
        $this->assertSame(5000, $pricing->config['extra_fee']);
        $this->assertCount(2, $pricing->config['quantity_tiers']);
        $this->assertSame(2500, $pricing->config['quantity_tiers'][1]['discount_per_unit']);

        $this->actingAs($user)
            ->post(route('admin.pricing.store', $product), [
                'price_model' => 'calculator',
                'status' => 'active',
                'effective_date' => '2026-12-01',
                'base_price' => 80000,
            ])
            ->assertRedirect(route('admin.pricing.index'));

        $this->assertSame(2, PricingConfiguration::query()->max('version'));
    }

    public function test_pricing_status_can_be_toggled(): void
    {
        $user = $this->makeUser(Role::ADMIN);
        $product = $this->makeProduct();

        $pricing = $product->pricingConfigurations()->create([
            'price_model' => 'fixed',
            'status' => PricingConfiguration::STATUS_ACTIVE,
            'effective_date' => '2026-11-01',
            'version' => 1,
            'base_price' => 50000,
            'config' => [],
        ]);

        $this->actingAs($user)
            ->patch(route('admin.pricing.toggle', $pricing))
            ->assertRedirect(route('admin.pricing.index'));

        $this->assertSame(PricingConfiguration::STATUS_INACTIVE, $pricing->fresh()->status);
    }

    public function test_guest_catalog_reflects_admin_publication_photos_and_active_pricing(): void
    {
        $this->withoutVite();
        Storage::fake('public');
        $admin = $this->makeUser(Role::ADMIN);
        $product = $this->makeProduct();
        $photo = file_get_contents(public_path('images/brand/yoloo-symbol.png'));

        $this->actingAs($admin)->put(route('admin.products.update', $product), [
            'category_id' => $product->category_id,
            'name' => $product->name,
            'status' => Product::STATUS_ACTIVE,
            'price_model' => 'fixed',
            'allows_direct_purchase' => '1',
            'specifications' => [['label' => 'Bahan', 'value' => 'Katun']],
            'images' => [
                UploadedFile::fake()->createWithContent('depan.png', $photo),
                UploadedFile::fake()->createWithContent('belakang.png', $photo),
            ],
        ])->assertRedirect();
        $this->post(route('admin.pricing.store', $product), [
            'price_model' => 'fixed',
            'status' => 'active',
            'effective_date' => today()->toDateString(),
            'base_price' => 50000,
        ])->assertRedirectToRoute('admin.pricing.index');

        $this->app['auth']->forgetGuards();
        $detail = $this->get('/produk/kaos-custom')->assertOk()
            ->assertSee('Katun')->assertSee('Rp50.000');
        $this->assertGuest();
        $this->assertDatabaseCount('product_images', 2);
        foreach ($product->images as $image) {
            Storage::disk('public')->assertExists($image->path);
            $detail->assertSee(Storage::disk('public')->url($image->path));
        }

        $this->actingAs($admin)->patch(route('admin.products.archive', $product))
            ->assertRedirectToRoute('admin.products.index');
        $this->app['auth']->forgetGuards();
        $this->get('/produk/kaos-custom')->assertNotFound();
        $this->get('/produk')->assertOk()->assertDontSee('Kaos Custom');
    }

    private function makeProduct(): Product
    {
        $category = Category::query()->create(['name' => 'Kaos', 'slug' => 'kaos', 'is_active' => true]);

        return Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Kaos Custom',
            'slug' => 'kaos-custom',
            'price_model' => 'calculator',
            'status' => Product::STATUS_ACTIVE,
        ]);
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
