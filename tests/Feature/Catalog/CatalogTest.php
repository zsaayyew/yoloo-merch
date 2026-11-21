<?php

namespace Tests\Feature\Catalog;

use App\Models\Product;
use App\Services\Catalog\ConfigurationPayloadBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seedCatalogFixtures();
    }

    public function test_public_list_filters_active_products_and_ignores_invalid_filters(): void
    {
        $this->get('/produk?q=STIKER&category=cetak&price_model=calculator')
            ->assertOk()->assertSee('Stiker kertas')->assertDontSee('Produk draf')->assertDontSee('Produk tersembunyi');
        $this->get('/produk?category=arsip')
            ->assertOk()->assertSee('Kategori yang dipilih sudah tidak tersedia')->assertSee('Stiker kertas');
        $this->get('/produk?q=%25_&price_model[]=fixed')->assertOk()->assertSee('Tidak ada produk pada halaman ini.');
        $this->get('/produk/kategori/cetak')->assertOk()->assertSee('Stiker kertas');
        $this->get('/produk/kategori/arsip')->assertNotFound();
    }

    public function test_product_detail_and_public_not_found_states(): void
    {
        $this->get('/produk/stiker')->assertOk()->assertSee('Stiker kertas')->assertSee('Finishing')
            ->assertSee('asset-satu.jpg')->assertSee('asset-dua.jpg')->assertSee('VK-270')->assertSee('Estimasi');
        $this->get('/produk/draf')->assertNotFound()->assertSee('Kembali ke katalog');
        $this->get('/produk/tersembunyi')->assertNotFound();
        $this->get('/produk/missing')->assertNotFound();
        $this->get('/produk/tetap')->assertOk()->assertSee('Harga tetap')->assertSee('Rp3.000');
        $this->get('/produk/mulai')->assertOk()->assertSee('Harga mulai dari')->assertSee('Rp4.000');
        $this->get('/produk/penawaran')->assertOk()->assertSee('Minta penawaran')->assertDontSee('Total estimasi');
    }

    public function test_estimate_enforces_options_minimum_activity_and_pricing(): void
    {
        $this->postJson('/produk/stiker/estimasi', ['quantity' => 10, 'option_value_ids' => [7]])
            ->assertOk()->assertJsonPath('unit_price', 2000)->assertJsonPath('total', 21000)->assertJsonPath('is_estimate', true);
        $this->postJson('/produk/stiker/estimasi', ['quantity' => 1, 'option_value_ids' => [7]])
            ->assertUnprocessable()->assertJsonValidationErrors('quantity')->assertJsonMissingPath('total');
        $this->postJson('/produk/stiker/estimasi', ['quantity' => 10, 'option_value_ids' => [7, 8]])
            ->assertUnprocessable()->assertJsonValidationErrors(['option_group_10', 'option_group_11']);
        $this->postJson('/produk/stiker/estimasi', ['quantity' => 10, 'option_value_ids' => [9]])
            ->assertUnprocessable()->assertJsonValidationErrors(['option_value_ids', 'option_group_10']);
        $this->postJson('/produk/stiker/estimasi', ['quantity' => 10, 'option_value_ids' => [7, 12]])
            ->assertUnprocessable()->assertJsonValidationErrors('option_group_10');
        $this->postJson('/produk/draf/estimasi', ['quantity' => 10])->assertNotFound();
        $this->postJson('/produk/penawaran/estimasi', ['quantity' => 10])
            ->assertUnprocessable()->assertJsonValidationErrors('pricing')->assertJsonMissingPath('total');
    }

    public function test_html_estimation_redirect_keeps_inputs_and_works_without_javascript(): void
    {
        $this->post('/produk/stiker/estimasi', ['quantity' => 10, 'option_value_ids' => [7, '']])
            ->assertRedirect(route('products.show', ['product' => 'stiker']).'#konfigurasi')
            ->assertSessionHas('catalog_estimate.total', 21000);
        $this->get('/produk/stiker')->assertOk()->assertSee('Rp21.000');
    }

    public function test_catalog_handoff_recomputes_cart_prices_and_flashes_quotation_snapshot(): void
    {
        Route::post('/keranjang', function (Request $request, ConfigurationPayloadBuilder $builder) {
            $product = Product::findOrFail($request->integer('product_id'));

            return $builder->build($product, $request->input('option_value_ids', []), $request->integer('quantity'));
        })->name('cart.store');

        Route::get('/custom-order', fn () => 'Form penawaran')->name('custom-orders.create');
        app('router')->getRoutes()->refreshNameLookups();

        $this->get('/produk/stiker')->assertSee(route('cart.store'));
        $this->postJson('/keranjang', [
            'product_id' => 1, 'quantity' => 10, 'option_value_ids' => [7], 'estimated_total' => 1,
        ])->assertOk()->assertExactJson([
            'product_id' => 1,
            'product_name_snapshot' => 'Stiker kertas',
            'price_model' => 'calculator',
            'options' => [['group' => 'Bahan', 'value' => 'Vinil', 'option_value_id' => 7]],
            'quantity' => 10,
            'estimated_unit_price' => 2000,
            'estimated_total' => 21000,
            'pricing_version' => 2,
        ]);

        $this->postJson('/keranjang', ['product_id' => 5, 'quantity' => 2])->assertJsonPath('estimated_total', 8000);

        $this->post('/produk/penawaran/estimasi', ['quantity' => 10, 'intent' => 'quotation'])
            ->assertRedirect(route('custom-orders.create', ['produk' => 'penawaran']))
            ->assertSessionHas('catalog_configuration', [
                'product_id' => 6,
                'product_name_snapshot' => 'Produk penawaran',
                'price_model' => 'quotation',
                'options' => [],
                'quantity' => 10,
                'estimated_unit_price' => null,
                'estimated_total' => null,
                'pricing_version' => null,
            ]);
    }

    /**
     * Skema katalog dibangun oleh migration asli; di sini hanya data contoh
     * yang dipakai oleh CatalogData, PriceCalculator, dan view publik.
     */
    private function seedCatalogFixtures(): void
    {
        $now = now();

        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Cetak', 'slug' => 'cetak', 'is_active' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Arsip', 'slug' => 'arsip', 'is_active' => false, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('products')->insert([
            [
                'id' => 1, 'category_id' => 1, 'name' => 'Stiker kertas', 'slug' => 'stiker',
                'description' => 'Stiker cetak custom.',
                'specifications' => json_encode(['Bahan' => 'Vinil', 'Kode' => 'VK-270']),
                'price_model' => 'calculator', 'allows_direct_purchase' => true, 'status' => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 2, 'category_id' => 1, 'name' => 'Produk draf', 'slug' => 'draf',
                'description' => null, 'specifications' => null,
                'price_model' => 'fixed', 'allows_direct_purchase' => true, 'status' => 'draft',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 3, 'category_id' => 2, 'name' => 'Produk tersembunyi', 'slug' => 'tersembunyi',
                'description' => null, 'specifications' => null,
                'price_model' => 'fixed', 'allows_direct_purchase' => true, 'status' => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 4, 'category_id' => 1, 'name' => 'Produk tetap', 'slug' => 'tetap',
                'description' => null, 'specifications' => null,
                'price_model' => 'fixed', 'allows_direct_purchase' => true, 'status' => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 5, 'category_id' => 1, 'name' => 'Produk mulai', 'slug' => 'mulai',
                'description' => null, 'specifications' => null,
                'price_model' => 'starting_from', 'allows_direct_purchase' => true, 'status' => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 6, 'category_id' => 1, 'name' => 'Produk penawaran', 'slug' => 'penawaran',
                'description' => null, 'specifications' => null,
                'price_model' => 'quotation', 'allows_direct_purchase' => false, 'status' => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);

        DB::table('product_images')->insert([
            ['id' => 1, 'product_id' => 1, 'path' => 'asset-satu.jpg', 'alt_text' => 'Stiker tampak depan', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'product_id' => 1, 'path' => 'asset-dua.jpg', 'alt_text' => 'Stiker tampak samping', 'is_primary' => false, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('option_groups')->insert([
            ['id' => 10, 'product_id' => 1, 'name' => 'Bahan', 'is_required' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'product_id' => 1, 'name' => 'Finishing', 'is_required' => false, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('option_values')->insert([
            ['id' => 7, 'option_group_id' => 10, 'label' => 'Vinil', 'is_active' => true, 'sort_order' => 1, 'forbidden_combinations' => json_encode([8]), 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'option_group_id' => 10, 'label' => 'Kertas', 'is_active' => true, 'sort_order' => 2, 'forbidden_combinations' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'option_group_id' => 11, 'label' => 'Laminasi', 'is_active' => true, 'sort_order' => 1, 'forbidden_combinations' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'option_group_id' => 10, 'label' => 'Vinil lama', 'is_active' => false, 'sort_order' => 3, 'forbidden_combinations' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('pricing_configurations')->insert([
            [
                'id' => 1, 'product_id' => 1, 'price_model' => 'calculator', 'status' => 'active',
                'effective_date' => '2026-01-01', 'version' => 2, 'base_price' => 1500,
                'config' => json_encode([
                    'minimum_quantity' => 10,
                    'option_surcharges' => ['7' => 500],
                    'extra_fee' => 1000,
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 2, 'product_id' => 4, 'price_model' => 'fixed', 'status' => 'active',
                'effective_date' => '2026-01-01', 'version' => 1, 'base_price' => 3000,
                'config' => null, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 3, 'product_id' => 5, 'price_model' => 'starting_from', 'status' => 'active',
                'effective_date' => '2026-01-01', 'version' => 1, 'base_price' => 4000,
                'config' => null, 'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }
}
