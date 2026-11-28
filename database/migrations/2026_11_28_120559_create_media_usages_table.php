<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : media_usages
 * Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
 * Catatan : Mencatat di mana satu berkas media dipakai (foto produk, gambar
 *           section konten, foto galeri) sehingga pustaka media bisa menampilkan
 *           pemakaian dan penggantian berkas tidak memutus halaman yang memakainya.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->morphs('usable');
            $table->string('field', 40)->default('image');
            $table->timestamps();

            $table->unique(['media_id', 'usable_type', 'usable_id', 'field'], 'media_usages_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_usages');
    }
};
