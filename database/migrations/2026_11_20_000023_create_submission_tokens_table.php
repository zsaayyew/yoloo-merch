<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PRD-03 ORD — Token sekali pakai anti kiriman ganda (ORD-FR-013, ORD-BR-011).
 * Bukan token untuk cek status pesanan (ORD-BR-013/014).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('form_type', 30);              // checkout | custom_order
            $table->string('session_id')->nullable()->index();

            // Hasil kiriman: kiriman kedua dengan token sama diarahkan ke konfirmasi ini.
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('order_request_id')->nullable()->constrained('order_requests')->nullOnDelete();

            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_tokens');
    }
};
