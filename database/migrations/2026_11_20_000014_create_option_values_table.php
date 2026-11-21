<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : option_values
 * Domain  : PRD-02 CAT - Catalog & Customization (Haidir)
 * Catatan : forbidden_combinations berisi daftar id nilai opsi yang tidak boleh
 *           dipilih bersamaan, mis. [8] pada nilai 7 (CAT-FR-006).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_group_id')->constrained('option_groups')->cascadeOnDelete();
            $table->string('label', 120);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('forbidden_combinations')->nullable();
            $table->timestamps();

            $table->index(['option_group_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_values');
    }
};
