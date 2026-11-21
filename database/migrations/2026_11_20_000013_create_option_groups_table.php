<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : option_groups
 * Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
 * Catatan : Grup opsi per produk (mis. Bahan, Finishing). is_required=true
 *           membuat pelanggan wajib memilih satu nilai pada grup tersebut.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('option_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('name', 120);
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_groups');
    }
};
