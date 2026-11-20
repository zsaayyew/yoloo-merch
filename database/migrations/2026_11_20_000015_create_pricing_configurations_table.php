<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : pricing_configurations
 * Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
 * Catatan : Konfigurasi tarif per produk dan versi. Yang dipakai website adalah
 *           baris berstatus active dengan effective_date terbaru (<= hari ini),
 *           lalu version tertinggi. config berisi aturan kalkulator:
 *           minimum_quantity, maximum_quantity, quantity_tiers[],
 *           option_surcharges{option_value_id: nominal}, matrix[],
 *           extra_fee, minimum_total.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('price_model', 30); // harus sama dengan products.price_model
            $table->string('status', 20)->default('active')->index(); // active | inactive
            $table->date('effective_date')->index();
            $table->unsignedInteger('version')->default(1);
            $table->unsignedBigInteger('base_price');
            $table->json('config')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_configurations');
    }
};
