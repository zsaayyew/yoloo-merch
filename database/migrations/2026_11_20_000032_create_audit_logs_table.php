<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bersama (koordinator) — Audit log append-only (Master PRD bab 13, AI_CONTEXT 7 poin 8).
 * Dipakai ADM (kelola akun Admin) dan FIN (QRIS, void, refund, batal, invoice, histori request K-7).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 100)->index();   // contoh: payment.void, user.deactivate
            $table->nullableMorphs('subject');        // subject_type + subject_id
            $table->text('reason')->nullable();
            $table->json('changes')->nullable();      // ['field' => ['old' => .., 'new' => ..]]
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent(); // tanpa updated_at: tidak pernah diubah
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
