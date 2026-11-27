<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : media, site_contents, site_settings, testimonials, collaborations,
 *           gallery_categories, gallery_items
 * Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
 * Catatan : Migration kerangka 2026_11_20_000003 s.d. 2026_11_20_000009 hanya berisi
 *           id + timestamps. Migration ini menambahkan kolom final tanpa menghapus data,
 *           sehingga database development yang sudah berjalan ikut terbarui.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('path', 255)->after('id');
            $table->string('original_name', 255)->after('path');
            $table->string('mime_type', 100)->after('original_name');
            $table->unsignedBigInteger('size')->default(0)->after('mime_type');
            $table->string('alt_text', 200)->nullable()->after('size');
            $table->foreignId('uploaded_by')->nullable()->after('alt_text')
                ->constrained('users')->nullOnDelete();
            $table->index('created_at');
        });

        Schema::table('site_contents', function (Blueprint $table) {
            $table->string('page', 30)->after('id');
            $table->string('section', 60)->after('page');
            $table->string('title', 200)->nullable()->after('section');
            $table->string('subtitle', 255)->nullable()->after('title');
            $table->text('body')->nullable()->after('subtitle');
            $table->string('image_path', 255)->nullable()->after('body');
            $table->boolean('is_published')->default(true)->after('image_path');
            $table->unsignedInteger('sort_order')->default(0)->after('is_published');

            $table->unique(['page', 'section']);
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('setting_key', 100)->after('id');
            $table->text('setting_value')->nullable()->after('setting_key');
            $table->string('setting_group', 50)->default('umum')->after('setting_value');
            $table->string('setting_label', 120)->after('setting_group');
            $table->string('input_type', 20)->default('text')->after('setting_label');

            $table->unique('setting_key');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('customer_name', 120)->after('id');
            $table->string('customer_role', 120)->nullable()->after('customer_name');
            $table->unsignedTinyInteger('rating')->default(5)->after('customer_role');
            $table->text('quote')->after('rating');
            $table->string('photo_path', 255)->nullable()->after('quote');
            $table->boolean('is_published')->default(false)->after('photo_path');
            $table->unsignedInteger('sort_order')->default(0)->after('is_published');

            $table->index(['is_published', 'sort_order']);
        });

        Schema::table('collaborations', function (Blueprint $table) {
            $table->string('partner_name', 160)->after('id');
            $table->text('description')->nullable()->after('partner_name');
            $table->string('logo_path', 255)->nullable()->after('description');
            $table->string('website_url', 255)->nullable()->after('logo_path');
            $table->boolean('is_published')->default(false)->after('website_url');
            $table->unsignedInteger('sort_order')->default(0)->after('is_published');

            $table->index(['is_published', 'sort_order']);
        });

        Schema::table('gallery_categories', function (Blueprint $table) {
            $table->string('name', 120)->after('id');
            $table->string('slug', 140)->unique()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->boolean('is_active')->default(true)->index()->after('description');
            $table->unsignedInteger('sort_order')->default(0)->after('is_active');
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->foreignId('gallery_category_id')->nullable()->after('id')
                ->constrained('gallery_categories')->nullOnDelete();
            $table->string('title', 160)->after('gallery_category_id');
            $table->text('caption')->nullable()->after('title');
            $table->string('image_path', 255)->nullable()->after('caption');
            $table->boolean('is_published')->default(false)->index()->after('image_path');
            $table->unsignedInteger('sort_order')->default(0)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gallery_category_id');
            $table->dropColumn(['title', 'caption', 'image_path', 'is_published', 'sort_order']);
        });

        Schema::table('gallery_categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropIndex(['is_active']);
            $table->dropColumn(['name', 'slug', 'description', 'is_active', 'sort_order']);
        });

        Schema::table('collaborations', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'sort_order']);
            $table->dropColumn(['partner_name', 'description', 'logo_path', 'website_url', 'is_published', 'sort_order']);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'sort_order']);
            $table->dropColumn(['customer_name', 'customer_role', 'rating', 'quote', 'photo_path', 'is_published', 'sort_order']);
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropUnique(['setting_key']);
            $table->dropColumn(['setting_key', 'setting_value', 'setting_group', 'setting_label', 'input_type']);
        });

        Schema::table('site_contents', function (Blueprint $table) {
            $table->dropUnique(['page', 'section']);
            $table->dropColumn(['page', 'section', 'title', 'subtitle', 'body', 'image_path', 'is_published', 'sort_order']);
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropConstrainedForeignId('uploaded_by');
            $table->dropColumn(['path', 'original_name', 'mime_type', 'size', 'alt_text']);
        });
    }
};
