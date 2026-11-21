<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * PRD-03 ORD — Data produk DEMO untuk mengetes keranjang dan checkout di laptop.
 *
 * BUKAN data bisnis asli: nama berakhiran "(Demo)" dan harga hanya contoh.
 * Tidak dipanggil dari DatabaseSeeder, jadi tidak ikut ke data tim.
 * Jalankan manual:  php artisan db:seed --class=OrderingDemoSeeder
 *
 * Isi:
 *   1. Stiker Vinyl Custom (Demo)   -> calculator, opsi Ukuran + Finishing, min 50 pcs
 *   2. Gantungan Kunci Akrilik (Demo) -> fixed, tanpa opsi
 *   3. Kaos Sablon Custom (Demo)    -> quotation, tidak bisa masuk keranjang
 *
 * Ditulis dengan DB::table() supaya tidak bergantung pada $fillable model milik CAT/ADM.
 */
class OrderingDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('categories')->where('slug', 'demo-ord')->exists()) {
            $this->command?->warn('Data demo ORD sudah ada. Jalankan migrate:fresh dulu jika ingin mengulang.');

            return;
        }

        $now = now();
        $today = $now->toDateString();

        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Demo ORD',
            'slug' => 'demo-ord',
            'description' => 'Kategori contoh untuk pengujian fitur pemesanan.',
            'is_active' => true,
            'sort_order' => 99,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ---------- 1. Produk calculator dengan opsi ----------
        $stickerId = $this->product($categoryId, 'Stiker Vinyl Custom (Demo)', 'demo-stiker-vinyl', 'calculator', true);

        $sizeGroup = $this->group($stickerId, 'Ukuran', 1);
        $size5 = $this->value($sizeGroup, '5 x 5 cm', 1);
        $size10 = $this->value($sizeGroup, '10 x 10 cm', 2);

        $finishGroup = $this->group($stickerId, 'Finishing', 2);
        $glossy = $this->value($finishGroup, 'Glossy', 1);
        $doff = $this->value($finishGroup, 'Doff', 2);

        DB::table('pricing_configurations')->insert([
            'product_id' => $stickerId,
            'price_model' => 'calculator',
            'status' => 'active',
            'effective_date' => $today,
            'version' => 1,
            'base_price' => 1500,
            'config' => json_encode([
                'minimum_quantity' => 50,
                'option_surcharges' => [
                    (string) $size10 => 2000,
                    (string) $doff => 300,
                ],
                'quantity_tiers' => [
                    ['min' => 50, 'max' => 99, 'discount_per_unit' => 0],
                    ['min' => 100, 'max' => null, 'discount_per_unit' => 200],
                ],
                'extra_fee' => 0,
                'minimum_total' => 0,
            ]),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ---------- 2. Produk harga tetap tanpa opsi ----------
        $keychainId = $this->product($categoryId, 'Gantungan Kunci Akrilik (Demo)', 'demo-gantungan-akrilik', 'fixed', true);

        DB::table('pricing_configurations')->insert([
            'product_id' => $keychainId,
            'price_model' => 'fixed',
            'status' => 'active',
            'effective_date' => $today,
            'version' => 1,
            'base_price' => 15000,
            'config' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ---------- 3. Produk quotation (harus ditolak keranjang) ----------
        $this->product($categoryId, 'Kaos Sablon Custom (Demo)', 'demo-kaos-sablon', 'quotation', false);

        $this->command?->info('Data demo ORD dibuat: 3 produk di kategori "Demo ORD".');
        $this->command?->line("ID opsi stiker: 5x5={$size5}, 10x10={$size10}, Glossy={$glossy}, Doff={$doff}");
    }

    private function product(int $categoryId, string $name, string $slug, string $priceModel, bool $direct): int
    {
        return DB::table('products')->insertGetId([
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => $slug,
            'description' => 'Produk contoh untuk pengujian. Bukan data produk asli YOLOO Merch.',
            'specifications' => null,
            'price_model' => $priceModel,
            'allows_direct_purchase' => $direct,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function group(int $productId, string $name, int $sort): int
    {
        return DB::table('option_groups')->insertGetId([
            'product_id' => $productId,
            'name' => $name,
            'is_required' => true,
            'sort_order' => $sort,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function value(int $groupId, string $label, int $sort): int
    {
        return DB::table('option_values')->insertGetId([
            'option_group_id' => $groupId,
            'label' => $label,
            'is_active' => true,
            'sort_order' => $sort,
            'forbidden_combinations' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
