<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\OptionValue;
use App\Models\PricingConfiguration;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CatalogDemoSeeder extends Seeder
{
    private const STATE_KEY = 'catalog_demo_fixture_ids';

    private const STATE_GROUP = 'catalog_demo';

    private const LEGACY_CATEGORY_DESCRIPTION = 'Kategori contoh untuk pengujian fitur pemesanan.';

    private const LEGACY_PRODUCT_DESCRIPTION = 'Produk contoh untuk pengujian. Bukan data produk asli YOLOO Merch.';

    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            throw new RuntimeException('Seeder katalog dummy hanya dapat dijalankan pada lingkungan local atau testing.');
        }

        $fixtures = require __DIR__.'/data/catalog-demo.php';

        DB::transaction(function () use ($fixtures): void {
            $registration = SiteSetting::query()->lockForUpdate()->firstOrNew(['setting_key' => self::STATE_KEY]);
            if ($registration->exists && $registration->setting_group !== self::STATE_GROUP) {
                throw new RuntimeException('Kunci internal katalog dummy sudah digunakan oleh pengaturan lain.');
            }

            // ID fixture tetap bisa ditemukan setelah admin mengubah nama, slug, atau foto.
            $registered = json_decode($registration->setting_value ?? '{}', true, flags: JSON_THROW_ON_ERROR);
            $legacy = Category::query()->where([
                'slug' => 'demo-ord',
                'name' => 'Demo ORD',
                'description' => self::LEGACY_CATEGORY_DESCRIPTION,
            ])->lockForUpdate()->first();
            $legacyProducts = $this->legacyProducts($legacy, $fixtures['products']);
            $reusableLegacy = $legacy && $legacy->products()->count() === $legacyProducts->count() ? $legacy : null;

            $categories = $this->seedCategories($fixtures['categories'], $registered['categories'] ?? [], $reusableLegacy);
            $productIds = $this->seedProducts($fixtures['products'], $registered['products'] ?? [], $categories, $legacyProducts);

            if ($legacy && $legacy->slug === 'demo-ord') {
                $this->retireLegacyCategory($legacy);
            }

            $registration->fill([
                'setting_group' => self::STATE_GROUP,
                'setting_label' => 'ID fixture katalog dummy',
                'input_type' => 'text',
                'setting_value' => json_encode([
                    'categories' => $categories->map->getKey()->all(),
                    'products' => $productIds,
                ], JSON_THROW_ON_ERROR),
            ])->save();
        }, attempts: 3);

        $this->command?->info('Paket katalog dummy selesai diproses.');
    }

    private function legacyProducts(?Category $category, array $fixtures): Collection
    {
        $namesBySlug = collect($fixtures)->whereNotNull('legacy_slug')->mapWithKeys(
            fn (array $fixture): array => [$fixture['legacy_slug'] => $fixture['name'].' (Demo)'],
        );

        return $category === null ? collect() : $category->products()
            ->where('description', self::LEGACY_PRODUCT_DESCRIPTION)
            ->whereIn('slug', $namesBySlug->keys())
            ->get()->filter(fn (Product $product): bool => $namesBySlug[$product->slug] === $product->name)
            ->keyBy('slug');
    }

    private function seedCategories(array $fixtures, array $registered, ?Category $reusableLegacy): Collection
    {
        $categories = collect();

        foreach ($fixtures as $key => $fixture) {
            $category = Category::find($registered[$key] ?? null)
                ?? Category::query()->where('name', $fixture['name'])->orderBy('id')->first();

            if ($category === null) {
                $category = $reusableLegacy ?? new Category;
                $category->fill($fixture + [
                    'slug' => Category::uniqueSlug($fixture['name'], $category->id),
                    'is_active' => true,
                ])->save();
                $reusableLegacy = null;
            }

            if (! $category->is_active) {
                $this->command?->warn("Kategori {$category->name} sedang nonaktif. Status dari admin dipertahankan.");
            }

            $categories->put($key, $category);
        }

        return $categories;
    }

    private function seedProducts(array $fixtures, array $registered, Collection $categories, Collection $legacyProducts): array
    {
        $ids = [];

        foreach ($fixtures as $key => $fixture) {
            $path = "catalog-demo/{$key}.jpg";
            $product = Product::find($registered[$key] ?? null);
            if ($product) {
                if ($product->images()->where('path', $path)->exists()) {
                    $this->ensurePhoto($path);
                }
                $ids[$key] = $product->id;

                continue;
            }

            $slug = $fixture['legacy_slug'] ?? $key;
            $legacy = $legacyProducts->get($slug);
            $conflict = Product::query()
                ->when($legacy, fn ($query) => $query->whereKeyNot($legacy->getKey()))
                ->where(fn ($query) => $query->where('name', $fixture['name'])->orWhereIn('slug', [$slug, $key]))
                ->exists();

            if ($conflict) {
                $this->command?->warn("{$fixture['name']} dilewati karena nama atau slug sudah digunakan.");

                continue;
            }

            $product = $legacy ?? new Product([
                'slug' => $slug,
                'price_model' => $fixture['price_model'],
                'allows_direct_purchase' => in_array($fixture['price_model'], ['fixed', 'calculator'], true),
                'status' => Product::STATUS_ACTIVE,
            ]);
            $product->fill([
                'category_id' => $categories[$fixture['category']]->id,
                'name' => $fixture['name'],
                'description' => $fixture['description'],
                'specifications' => $product->specifications ?? $fixture['specifications'],
            ])->save();

            $optionIds = $this->seedOptions($product, $fixture['options'], $fixture['forbidden'] ?? []);
            $this->seedPricing($product, $fixture, $optionIds);

            if (! $product->images()->exists()) {
                $this->ensurePhoto($path);
                $product->images()->create(['path' => $path, 'alt_text' => $fixture['alt_text'], 'is_primary' => true, 'sort_order' => 1]);
            }

            $ids[$key] = $product->id;
        }

        return $ids;
    }

    private function seedOptions(Product $product, array $groups, array $forbidden): array
    {
        $existing = $product->optionGroups()->with('values')->get()->keyBy('name');
        $create = $existing->isEmpty();
        $options = [];

        foreach ($groups as $position => $fixture) {
            $group = $create ? $product->optionGroups()->create([
                'name' => $fixture['name'],
                'is_required' => $fixture['required'],
                'sort_order' => $position + 1,
            ]) : $existing->get($fixture['name']);
            $sort = 1;

            foreach ($fixture['values'] as $key => $label) {
                $value = $create ? $group->values()->create([
                    'label' => $label,
                    'is_active' => true,
                    'sort_order' => $sort++,
                ]) : $group?->values->firstWhere('label', $label);

                if ($value) {
                    $options[$key] = $value;
                }
            }
        }

        $ids = array_map(fn (OptionValue $value): int => $value->id, $options);
        if ($create) {
            foreach ($forbidden as $source => $blocked) {
                $options[$source]->update([
                    'forbidden_combinations' => array_map(fn (string $key): int => $ids[$key], $blocked),
                ]);
            }
        }

        return $ids;
    }

    private function seedPricing(Product $product, array $fixture, array $optionIds): void
    {
        if (! isset($fixture['price']) || $product->price_model !== $fixture['price_model'] || $product->pricingConfigurations()->exists()) {
            return;
        }

        $config = $fixture['pricing'] ?? [];
        if (isset($config['option_surcharges'])) {
            $surcharges = [];
            foreach ($config['option_surcharges'] as $key => $amount) {
                if (! isset($optionIds[$key])) {
                    $this->command?->warn("Tarif {$product->name} dilewati karena opsi lama berbeda.");

                    return;
                }
                $surcharges[$optionIds[$key]] = $amount;
            }
            $config['option_surcharges'] = $surcharges;
        }

        $product->pricingConfigurations()->create([
            'price_model' => $fixture['price_model'],
            'status' => PricingConfiguration::STATUS_ACTIVE,
            'effective_date' => '2026-01-01',
            'version' => 1,
            'base_price' => $fixture['price'],
            'config' => $config ?: null,
        ]);
    }

    private function ensurePhoto(string $path): void
    {
        $disk = Storage::disk('public');
        if ($disk->exists($path)) {
            return;
        }

        $source = __DIR__.'/assets/catalog-demo/'.basename($path);
        if (! is_readable($source) || ! $disk->put($path, file_get_contents($source))) {
            throw new RuntimeException("Foto katalog dummy tidak bisa disalin: {$path}");
        }
    }

    private function retireLegacyCategory(Category $legacy): void
    {
        if ($legacy->products()->exists()) {
            $this->command?->warn('Kategori lama masih memiliki produk lain. Kategori tersebut dipertahankan.');

            return;
        }

        $legacy->update(['name' => 'Arsip Kategori Lama', 'is_active' => false]);
    }
}
