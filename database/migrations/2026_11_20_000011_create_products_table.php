<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : products
 * Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
 * Catatan : Hanya produk berstatus active pada kategori aktif yang tampil publik.
 *           price_model: fixed | starting_from | calculator | quotation (PriceModel).
 *           allows_direct_purchase=false berarti produk hanya lewat permintaan penawaran.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Produk tidak pernah dihapus permanen (diarsipkan), jadi restrict aman.
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('name', 160);
            $table->string('slug', 180)->unique();
            $table->text('description')->nullable();
            // Spesifikasi bebas: {"Bahan": "Vinil", "Kode": "VK-270"}.
            $table->json('specifications')->nullable();
            $table->string('price_model', 30)->default('quotation')->index();
            $table->boolean('allows_direct_purchase')->default(true);
            $table->string('status', 20)->default('draft')->index(); // ProductStatus: draft | active | archived
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
