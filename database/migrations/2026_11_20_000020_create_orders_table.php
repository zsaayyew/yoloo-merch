<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bersama ORD + FIN — Order (AI_CONTEXT 6.1).
 * Direct purchase website dibuat ORD (status awaiting_confirmation, payment_status unpaid).
 * Order hasil konversi custom dan transaksi manual dibuat FIN.
 * Uang dalam Rupiah tanpa desimal.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();                 // WEB-YYMMDD-XXXX / nomor manual FIN
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->string('type');                               // OrderType
            $table->string('channel')->default('website');        // OrderChannel
            $table->foreignId('source_request_id')->nullable()->unique()
                ->constrained('order_requests')->nullOnDelete();  // konversi hanya sekali

            $table->string('status')->index();                    // OrderStatus
            $table->string('payment_status')->default('unpaid')->index(); // PaymentStatus (dihitung FIN)

            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('discount')->default(0);
            $table->unsignedBigInteger('additional_fee')->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->unsignedBigInteger('estimated_total')->nullable();
            $table->unsignedBigInteger('agreed_dp_amount')->nullable();
            $table->text('payment_terms_note')->nullable();

            $table->string('fulfillment')->default('pickup');     // selalu pickup
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('ordered_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
