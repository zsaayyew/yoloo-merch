<?php

namespace Tests\Feature\Catalog;

use App\Models\Category;
use App\Models\Customer;
use App\Models\OrderRequest;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\CatalogDemoSeeder;
use Database\Seeders\OrderingDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class CatalogDemoSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Storage::fake('public');
    }

    public function test_repeated_seeding_publishes_eight_complete_products_with_four_price_models(): void
    {
        $this->seed(CatalogDemoSeeder::class);
        $this->seed(OrderingDemoSeeder::class);

        $this->assertSame(['Apparel', 'Gantungan Kunci', 'Stiker'], Category::orderBy('name')->pluck('name')->all());
        $this->assertDatabaseCount('products', 8);
        $this->assertDatabaseCount('product_images', 8);
        $this->assertDatabaseCount('pricing_configurations', 6);
        $this->assertDatabaseCount('option_groups', 13);
        $this->assertDatabaseCount('option_values', 28);

        $listing = $this->get('/produk')->assertOk();
        $products = Product::with(['images', 'pricingConfigurations', 'optionGroups.values'])->get();
        $this->assertSame([
            'calculator' => 2,
            'fixed' => 2,
            'quotation' => 2,
            'starting_from' => 2,
        ], $products->countBy('price_model')->sortKeys()->all());

        foreach ($products as $product) {
            $image = $product->images->sole();
            Storage::disk('public')->assertExists($image->path);
            $listing->assertSee($product->name);
            $detail = $this->get(route('products.show', ['product' => $product->slug]))->assertOk()
                ->assertSee($product->description)
                ->assertSee('Spesifikasi')
                ->assertSee($image->alt_text)
                ->assertSee(Storage::disk('public')->url($image->path));
            $this->assertNotEmpty($product->specifications);
            $this->assertNotEmpty($product->optionGroups);
            $this->assertTrue($image->is_primary);

            if ($product->price_model === 'quotation') {
                $this->assertTrue($product->pricingConfigurations->isEmpty());
                $detail->assertSee('Minta penawaran')->assertDontSee('data-catalog-cart', false);
            }
        }

        $this->actingAs($this->makeAdmin());
        $adminProducts = $this->get(route('admin.products.index'))->assertOk();
        $adminPricing = $this->get(route('admin.pricing.index'))->assertOk();
        $this->get(route('admin.categories.index'))->assertOk()
            ->assertSeeText('Apparel')->assertSeeText('Gantungan Kunci')->assertSeeText('Stiker');
        foreach ($products as $product) {
            $adminProducts->assertSeeText($product->name);
            $adminPricing->assertSeeText($product->name);
        }
    }

    public function test_transparent_sticker_rules_calculate_a_minimum_and_reject_a_forbidden_combination(): void
    {
        $this->seed(CatalogDemoSeeder::class);
        $product = Product::where('slug', 'stiker-transparan')->firstOrFail();
        $values = $product->optionGroups()->with('values')->get()->flatMap->values->pluck('id', 'label');
        $estimate = route('products.estimate', ['product' => $product->slug]);

        $this->postJson($estimate, [
            'quantity' => 30,
            'option_value_ids' => [$values['3 x 3 cm'], $values['Cetak warna']],
        ])->assertOk()->assertJsonPath('unit_price', 1800)->assertJsonPath('total', 65000)
            ->assertJsonPath('breakdown.minimum_total_adjustment', 6000);

        $this->postJson($estimate, [
            'quantity' => 100,
            'option_value_ids' => [$values['5 x 5 cm'], $values['Warna dengan tinta putih']],
        ])->assertOk()->assertJsonPath('unit_price', 2950)->assertJsonPath('total', 300000);

        $this->postJson($estimate, [
            'quantity' => 30,
            'option_value_ids' => [$values['3 x 3 cm'], $values['Warna dengan tinta putih']],
        ])->assertUnprocessable()->assertJsonMissingPath('total');
    }

    public function test_legacy_update_reuses_product_category_and_option_ids_and_preserves_order_history(): void
    {
        $legacyCategory = Category::create([
            'name' => 'Demo ORD',
            'slug' => 'demo-ord',
            'description' => 'Kategori contoh untuk pengujian fitur pemesanan.',
        ]);
        $product = Product::create([
            'category_id' => $legacyCategory->id,
            'name' => 'Stiker Vinyl Custom (Demo)',
            'slug' => 'demo-stiker-vinyl',
            'description' => 'Produk contoh untuk pengujian. Bukan data produk asli YOLOO Merch.',
            'price_model' => 'calculator',
            'status' => Product::STATUS_ACTIVE,
        ]);
        $group = $product->optionGroups()->create(['name' => 'Ukuran', 'is_required' => true]);
        $size = $group->values()->create(['label' => '5 x 5 cm', 'is_active' => true]);
        $rate = $product->pricingConfigurations()->create([
            'price_model' => 'calculator',
            'effective_date' => '2026-01-01',
            'status' => 'active',
            'base_price' => 1750,
            'version' => 2,
            'config' => ['minimum_quantity' => 25],
        ]);
        $customer = Customer::resolveFromContact(['name' => 'Pelanggan Uji', 'whatsapp_number' => '6281234567890']);
        $request = OrderRequest::create([
            'customer_id' => $customer->id,
            'code' => 'REQ-260101-0001',
            'channel' => 'website',
            'status' => 'new',
            'product_id' => $product->id,
            'product_name_snapshot' => $product->name,
            'description' => 'Referensi pesanan sebelum pembaruan katalog.',
            'quantity' => 25,
            'submitted_at' => now(),
        ]);

        $this->seed(CatalogDemoSeeder::class);

        $this->assertDatabaseCount('categories', 3);
        $this->assertDatabaseCount('products', 8);
        $this->assertSame('Stiker', $legacyCategory->fresh()->name);
        $this->assertSame('Stiker Vinyl Custom', $product->fresh()->name);
        $this->assertSame($legacyCategory->id, $product->fresh()->category_id);
        $this->assertSame($size->id, $product->optionGroups()->sole()->values()->sole()->id);
        $this->assertSame($rate->id, $product->pricingConfigurations()->sole()->id);
        $this->assertSame(1750, $rate->fresh()->base_price);
        $this->assertSame($product->id, $request->fresh()->product_id);
        $this->assertSame('Stiker Vinyl Custom (Demo)', $request->fresh()->product_name_snapshot);
        $this->get('/produk/demo-stiker-vinyl')->assertOk()->assertSee('Stiker Vinyl Custom')->assertDontSee('(Demo)');
    }

    public function test_business_product_and_admin_fixture_edits_survive_reseeding(): void
    {
        $category = Category::create(['name' => 'Apparel', 'slug' => 'pakaian', 'description' => 'Kategori toko']);
        $business = Product::create([
            'category_id' => $category->id,
            'name' => 'Kaos Polos',
            'slug' => 'kaos-polos-resmi',
            'price_model' => 'quotation',
            'status' => Product::STATUS_ACTIVE,
            'description' => 'Produk bisnis',
        ]);
        $this->seed(CatalogDemoSeeder::class);
        $fixture = Product::where('slug', 'stiker-hologram')->firstOrFail();
        $asset = $fixture->images()->sole();

        $this->actingAs($this->makeAdmin())->put(route('admin.products.update', $fixture), [
            'category_id' => $fixture->category_id,
            'name' => 'Stiker Hologram Koleksi',
            'description' => 'Deskripsi dari admin',
            'price_model' => 'starting_from',
            'status' => Product::STATUS_ACTIVE,
        ])->assertRedirectToRoute('admin.products.edit', $fixture);
        $this->delete(route('admin.products.images.destroy', [$fixture, $asset]))->assertRedirect();
        $this->seed(CatalogDemoSeeder::class);

        $this->assertSame('Produk bisnis', $business->fresh()->description);
        $this->assertSame('Kategori toko', $category->fresh()->description);
        $this->assertDatabaseCount('products', 8);
        $this->assertDatabaseMissing('products', ['slug' => 'stiker-hologram']);
        $this->assertSame('Deskripsi dari admin', $fixture->fresh()->description);
        $this->assertFalse($fixture->images()->exists());
        $this->get('/produk/stiker-hologram-koleksi')->assertOk()->assertSee('Stiker Hologram Koleksi');
    }

    public function test_seeder_refuses_to_run_in_production(): void
    {
        $this->app->instance('env', 'production');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Seeder katalog dummy hanya dapat dijalankan');

        app(CatalogDemoSeeder::class)->run();
    }

    private function makeAdmin(): User
    {
        $role = Role::create(['name' => Role::ADMIN, 'label' => 'Admin']);

        return User::factory()->create(['role_id' => $role->id, 'is_active' => true]);
    }
}
