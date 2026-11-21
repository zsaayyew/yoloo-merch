<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bersama ORD + FIN — Item order berisi snapshot (AI_CONTEXT 6.1).
 * Snapshot tidak di-update setelah dibuat; perubahan katalog tidak mengubah transaksi lama.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name_snapshot');
            $table->json('options_snapshot');
            $table->unsignedInteger('quantity');
            $table->string('price_model');                        // PriceModel
            $table->unsignedBigInteger('unit_price')->default(0);
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->string('price_status')->default('estimate');  // estimate | final
            $table->text('description')->nullable();              // untuk item custom
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
