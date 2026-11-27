<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : order_status_histories
 * Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
 * Catatan : histori perubahan status pengerjaan order (FIN-FR-010, FIN-BR-007).
 *           Append-only: tidak ada updated_at, baris tidak diubah atau dihapus.
 *           Histori status request dicatat di audit_logs (keputusan K-7).
 *           Kolom mengikuti Master PRD bagian J (Order Status History).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->restrictOnDelete();
            $table->string('previous_status')->nullable();          // OrderStatus (kosong untuk catatan awal)
            $table->string('new_status');                           // OrderStatus
            $table->foreignId('changed_by_user_id')->nullable()     // kosong jika diubah oleh sistem
                ->constrained('users')->nullOnDelete();
            $table->timestamp('changed_at');
            $table->text('note')->nullable();                       // catatan / alasan (wajib saat dibatalkan)

            $table->index(['order_id', 'changed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
