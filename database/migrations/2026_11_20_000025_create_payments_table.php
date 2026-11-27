<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : payments
 * Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
 * Catatan : catatan pembayaran QRIS yang sudah diverifikasi manual (FIN-FR-008, FIN-FR-009).
 *           Tidak pernah dihapus: salah catat diselesaikan dengan Void oleh Owner (FIN-BR-009).
 *           Uang dalam Rupiah tanpa desimal. submission_key mencegah pengiriman ganda form.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->restrictOnDelete();
            $table->date('payment_date');                               // tanggal bayar
            $table->unsignedBigInteger('amount');                       // Rupiah bulat, > 0
            $table->string('method', 30)->default('qris');              // versi pertama: QRIS saja
            $table->string('reference', 100)->nullable();               // referensi transaksi QRIS
            $table->text('note')->nullable();                           // DP / bertahap / pelunasan
            $table->string('status')->default('valid')->index();        // PaymentRecordStatus

            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Diisi saat Void (hanya Owner).
            $table->text('void_reason')->nullable();
            $table->foreignId('voided_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('voided_at')->nullable();

            // Token unik per tampilan form untuk mencegah pengiriman ganda.
            $table->string('submission_key', 64)->nullable()->unique();

            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
