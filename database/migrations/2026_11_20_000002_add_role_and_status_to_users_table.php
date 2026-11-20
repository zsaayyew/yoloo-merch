<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : users (tabel bawaan Laravel, TIDAK diubah file aslinya)
 * Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
 * Catatan : kerangka. Nanti menambah role_id (FK ke roles) dan status akun
 *           aktif/nonaktif sesuai ADM bagian J.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // TODO: role_id dan status akun.
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // TODO: kebalikan dari up().
        });
    }
};