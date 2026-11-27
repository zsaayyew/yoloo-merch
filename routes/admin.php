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
use App\Http\Controllers\Admin\Catalog\ProductOptionController;
use App\Http\Controllers\Admin\Content\AboutContentController;
use App\Http\Controllers\Admin\Content\CollaborationController;
use App\Http\Controllers\Admin\Content\GalleryCategoryController;
use App\Http\Controllers\Admin\Content\GalleryItemController;
use App\Http\Controllers\Admin\Content\HomepageContentController;
use App\Http\Controllers\Admin\Content\MediaController;
use App\Http\Controllers\Admin\Content\SiteSettingController;
use App\Http\Controllers\Admin\Content\TestimonialController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Sales\CustomerController;
use App\Http\Controllers\Admin\Sales\OrderController;
use App\Http\Controllers\Admin\Sales\QuotationController;
use App\Http\Controllers\Admin\Sales\RequestConversionController;
use App\Http\Controllers\Admin\Sales\RequestStatusController;
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
            Route::put('homepage', [HomepageContentController::class, 'update'])->name('homepage.update');

            Route::get('about', [AboutContentController::class, 'edit'])->name('about.edit');
            Route::put('about', [AboutContentController::class, 'update'])->name('about.update');

            Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');

            Route::resource('testimonials', TestimonialController::class)->except(['show']);
            Route::patch('testimonials/{testimonial}/publish', [TestimonialController::class, 'publish'])
                ->name('testimonials.publish');

            Route::resource('collaborations', CollaborationController::class)->except(['show']);
            Route::patch('collaborations/{collaboration}/publish', [CollaborationController::class, 'publish'])
                ->name('collaborations.publish');

            Route::resource('gallery-categories', GalleryCategoryController::class)->except(['show']);

            Route::resource('gallery', GalleryItemController::class)->except(['show']);
            Route::patch('gallery/{gallery}/publish', [GalleryItemController::class, 'publish'])
                ->name('gallery.publish');
        });

        // PRD-04 ADM - katalog dan konfigurasi harga (ADM-FR-004 s.d. ADM-FR-006)
        Route::resource('categories', CategoryController::class)->except(['show']);

        Route::resource('products', ProductController::class)->except(['show', 'destroy']);
        Route::patch('products/{product}/archive', [ProductController::class, 'archive'])
            ->name('products.archive');
        Route::patch('products/{product}/images/{image}/primary', [ProductController::class, 'primaryImage'])
            ->name('products.images.primary');
        Route::delete('products/{product}/images/{image}', [ProductController::class, 'destroyImage'])
            ->name('products.images.destroy');

        Route::get('products/{product}/options', [ProductOptionController::class, 'index'])
            ->name('products.options.index');
        Route::post('products/{product}/options', [ProductOptionController::class, 'storeGroup'])
            ->name('products.options.store');
        Route::delete('products/{product}/options/{group}', [ProductOptionController::class, 'destroyGroup'])
            ->name('products.options.destroy');
        Route::post('products/{product}/options/{group}/values', [ProductOptionController::class, 'storeValue'])
            ->name('products.options.values.store');
        Route::patch('products/{product}/options/{group}/values/{value}/toggle', [ProductOptionController::class, 'toggleValue'])
            ->name('products.options.values.toggle');
        Route::delete('products/{product}/options/{group}/values/{value}', [ProductOptionController::class, 'destroyValue'])
            ->name('products.options.values.destroy');

        Route::get('pricing', [PricingConfigurationController::class, 'index'])->name('pricing.index');
        Route::get('pricing/{product}/create', [PricingConfigurationController::class, 'create'])
            ->name('pricing.create');
        Route::post('pricing/{product}', [PricingConfigurationController::class, 'store'])
            ->name('pricing.store');
        Route::get('pricing/configuration/{pricing}/edit', [PricingConfigurationController::class, 'edit'])
            ->name('pricing.edit');
        Route::put('pricing/configuration/{pricing}', [PricingConfigurationController::class, 'update'])
            ->name('pricing.update');
        Route::patch('pricing/configuration/{pricing}/toggle', [PricingConfigurationController::class, 'toggle'])
            ->name('pricing.toggle');

        // PRD-04 ADM - media (ADM-FR-008)
        Route::get('media', [MediaController::class, 'index'])->name('media.index');
        Route::post('media', [MediaController::class, 'store'])->name('media.store');
        Route::patch('media/{media}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

        // PRD-04 ADM - user management, hanya Owner (ADM-FR-009)
        Route::resource('users', UserController::class)
            ->except(['show'])
            ->middleware('role:owner');

        // PRD-05 FIN - daftar & detail order/request (FIN-FR-001, FIN-FR-002)
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])
            ->whereNumber('order')->name('orders.show');
        Route::get('requests/{orderRequest}', [OrderController::class, 'showRequest'])
            ->whereNumber('orderRequest')->name('requests.show');

        // PRD-05 FIN - review request, penawaran, konversi sekali (FIN-FR-003, 004, 005)
        Route::patch('requests/{orderRequest}/status', [RequestStatusController::class, 'update'])
            ->whereNumber('orderRequest')->name('requests.status.update');
        Route::put('requests/{orderRequest}/quotation', [QuotationController::class, 'update'])
            ->whereNumber('orderRequest')->name('requests.quotation.update');
        Route::post('requests/{orderRequest}/convert', [RequestConversionController::class, 'store'])
            ->whereNumber('orderRequest')->name('requests.convert');

        // PRD-05 FIN - pelanggan (FIN-FR-011)
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])
            ->whereNumber('customer')->name('customers.show');
        Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])
            ->whereNumber('customer')->name('customers.edit');
        Route::put('customers/{customer}', [CustomerController::class, 'update'])
            ->whereNumber('customer')->name('customers.update');
    });
});

// PRD-05 FIN - (sisa) status order, pembatalan, pembayaran, refund, invoice, pengeluaran, laporan, QRIS
