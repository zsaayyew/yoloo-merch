<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PRD-03 ORD — Lampiran desain/referensi (ORD-FR-011, ORD-BR-010).
 * Kolom minimum mengikuti AI_CONTEXT 6.1. Tambahan milik ORD: cart_item_id, submitted_at.
 * File disimpan di disk "local" (privat): tmp-uploads/ lalu order-attachments/.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_attachments', function (Blueprint $table) {
            $table->id();

            // Tautan setelah dikirim (salah satu terisi).
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained('order_items')->nullOnDelete();
            $table->foreignId('order_request_id')->nullable()->constrained('order_requests')->nullOnDelete();

            // Tautan sementara saat checkout (upload per item sebelum order ada).
            $table->foreignId('cart_item_id')->nullable()->constrained('cart_items')->nullOnDelete();

            $table->string('original_name');       // hanya metadata, tidak dipakai sebagai nama file
            $table->string('stored_path');         // nama acak di disk local
            $table->string('mime_type', 100);      // dari isi file, bukan dari ekstensi
            $table->unsignedBigInteger('size_bytes');
            $table->string('caption', 200)->nullable();

            $table->string('status')->default('temporary')->index(); // AttachmentStatus
            $table->string('session_token', 64)->nullable()->index(); // pemilik file sementara
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_attachments');
    }
};
