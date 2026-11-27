<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : refunds
 * Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
 * Catatan : pengembalian dana manual, parsial atau penuh, terhubung ke Payment sumber (FIN-FR-019).
 *           Hanya Owner yang mencatat. Hanya status Selesai yang mengurangi Pembayaran Bersih.
 *           Tidak pernah dihapus (FIN-BR-009).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->restrictOnDelete();
            $table->foreignId('payment_id')->constrained('payments')->restrictOnDelete();
            $table->date('refund_date');                                // tanggal refund
            $table->unsignedBigInteger('amount');                       // Rupiah bulat, > 0
            $table->string('method', 50);                               // metode pengembalian
            $table->string('reference', 100)->nullable();
            $table->text('reason');                                     // alasan wajib
            $table->string('status')->default('completed')->index();    // RefundStatus

            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('submission_key', 64)->nullable()->unique();

            $table->timestamps();

            $table->index(['order_id', 'status']);
            $table->index(['payment_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
