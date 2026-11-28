<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Nama seeder lama tetap bisa dipakai untuk menyiapkan data uji ORD.
 */
class OrderingDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CatalogDemoSeeder::class);
    }
}
