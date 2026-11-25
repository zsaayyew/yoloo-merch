<?php

/*
|--------------------------------------------------------------------------
| Route Admin Dashboard
|--------------------------------------------------------------------------
| Di-load dari routes/web.php dengan: require __DIR__.'/admin.php';
| Semua route di sini memakai prefix "admin", nama "admin.*",
| middleware auth + active, dan role:owner untuk aksi khusus Owner.
| Daftar route lengkap ada di dokumen rancangan bagian 4.5.
*/

use App\Http\Controllers\Admin\Account\UserController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Catalog\CategoryController;
use App\Http\Controllers\Admin\Catalog\PricingConfigurationController;
use App\Http\Controllers\Admin\Catalog\ProductController;
use App\Http\Controllers\Admin\Content\AboutContentController;
use App\Http\Controllers\Admin\Content\CollaborationController;
use App\Http\Controllers\Admin\Content\GalleryCategoryController;
use App\Http\Controllers\Admin\Content\GalleryItemController;
use App\Http\Controllers\Admin\Content\HomepageContentController;
use App\Http\Controllers\Admin\Content\MediaController;
use App\Http\Controllers\Admin\Content\SiteSettingController;
use App\Http\Controllers\Admin\Content\TestimonialController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// PRD-04 ADM - login/logout (ADM-FR-001)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])
        ->middleware('throttle:admin-login')
        ->name('login.store');

    Route::middleware(['auth', 'active'])->group(function () {
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('dashboard', DashboardController::class)->name('dashboard');

        // PRD-04 ADM - konten (ADM-FR-003, ADM-FR-007)
        Route::prefix('content')->name('content.')->group(function () {
            Route::get('homepage', [HomepageContentController::class, 'edit'])->name('homepage.edit');
            Route::get('about', [AboutContentController::class, 'edit'])->name('about.edit');
            Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
            Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
            Route::get('collaborations', [CollaborationController::class, 'index'])->name('collaborations.index');
            Route::get('gallery', [GalleryItemController::class, 'index'])->name('gallery.index');
            Route::get('gallery-categories', [GalleryCategoryController::class, 'index'])->name('gallery-categories.index');
        });

        // PRD-04 ADM - katalog dan konfigurasi harga (ADM-FR-004 s.d. ADM-FR-006)
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('pricing', [PricingConfigurationController::class, 'index'])->name('pricing.index');

        // PRD-04 ADM - media (ADM-FR-008)
        Route::get('media', [MediaController::class, 'index'])->name('media.index');

        // PRD-04 ADM - user management, hanya Owner (ADM-FR-009)
        Route::get('users', [UserController::class, 'index'])
            ->middleware('role:owner')
            ->name('users.index');
    });
});

// PRD-05 FIN - order, pelanggan, pembayaran, refund, invoice, pengeluaran, laporan, QRIS
