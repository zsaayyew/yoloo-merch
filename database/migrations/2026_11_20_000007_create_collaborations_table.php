<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : collaborations
 * Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
 * Catatan : kolom final ditambahkan migration 2026_11_27_000001_add_adm_cms_columns
 *           agar database yang sudah berjalan ikut terbarui tanpa kehilangan data.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collaborations', function (Blueprint $table) {
            $table->id();
            // TODO: kolom sesuai Master PRD dan ERD final.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collaborations');
    }
};
