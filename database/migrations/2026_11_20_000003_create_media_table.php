<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : media
 * Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
 * Catatan : kerangka. Kolom final ditulis pemilik domain saat fitur dikerjakan,
 *           mengacu ke Master PRD (bagian J Data requirements) dan ERD bersama.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            // TODO: kolom sesuai Master PRD dan ERD final.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};