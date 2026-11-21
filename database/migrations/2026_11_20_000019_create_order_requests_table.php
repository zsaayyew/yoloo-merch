<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bersama ORD + FIN — Custom order request (AI_CONTEXT 6.1, keputusan K-1: tabel terpisah dari orders).
 * ORD membuat baris dengan status "new". Perubahan status, quotation, dan konversi dilakukan FIN.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_requests', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();                 // REQ-YYMMDD-XXXX
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->string('channel')->default('website');        // OrderChannel
            $table->string('status')->default('new')->index();    // RequestStatus

            // Referensi produk (opsional) + snapshot saat pengajuan.
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name_snapshot')->nullable();
            $table->json('options_snapshot')->nullable();
            $table->json('estimate_snapshot')->nullable();        // hanya informasi, bukan harga

            // Kebutuhan pelanggan.
            $table->text('description');
            $table->unsignedInteger('quantity');
            $table->text('specification')->nullable();
            $table->date('desired_deadline')->nullable();
            $table->unsignedBigInteger('budget')->nullable();
            $table->text('notes')->nullable();

            // Diisi FIN saat review.
            $table->unsignedBigInteger('quotation_amount')->nullable();
            $table->text('quotation_terms')->nullable();

            $table->timestamp('submitted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_requests');
    }
};
