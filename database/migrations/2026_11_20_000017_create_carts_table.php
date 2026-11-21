<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PRD-03 ORD — Keranjang guest (ORD-FR-003, keputusan K-5).
 * Keranjang diidentifikasi dengan token acak yang disimpan di cookie selama 7 hari.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();   // nilai cookie, acak (Str::random(64))
            $table->timestamp('expires_at')->index(); // dibersihkan oleh PurgeExpiredOrderingData
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
