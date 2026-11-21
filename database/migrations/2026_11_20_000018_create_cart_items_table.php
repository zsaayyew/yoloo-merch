<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PRD-03 ORD — Item keranjang (ORD-FR-001, ORD-FR-002).
 * Dibentuk dari ConfigurationPayload CAT (AI_CONTEXT bagian 7 poin 2).
 * Harga di sini hanya estimasi terakhir; selalu dihitung ulang via PriceCalculator.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            // Produk tidak pernah dihapus permanen (diarsipkan), jadi restrict aman.
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();

            $table->string('product_name_snapshot');
            $table->json('options_snapshot');      // [{group, value, option_value_id}, ...]
            $table->json('option_value_ids');      // [12, 15, ...] untuk hitung ulang PriceCalculator
            $table->string('configuration_hash', 64); // sha256(product_id + option_value_ids terurut)

            $table->unsignedInteger('quantity');
            $table->string('price_model');         // PriceModel: fixed|starting_from|calculator
            $table->unsignedBigInteger('estimated_unit_price')->nullable();
            $table->unsignedBigInteger('estimated_subtotal')->nullable();
            $table->unsignedInteger('pricing_version')->nullable();

            $table->timestamps();

            // Konfigurasi identik dalam satu keranjang digabung jumlahnya (FLOW-ORD-001 langkah 4).
            $table->unique(['cart_id', 'configuration_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
