<?php

/*
|--------------------------------------------------------------------------
| Route Public Website
|--------------------------------------------------------------------------
| Di-load dari routes/web.php dengan: require __DIR__.'/public.php';
| Kelompokkan per domain dengan komentar di bawah. Nama route TANPA prefix.
| Daftar route lengkap ada di dokumen rancangan bagian 4.5.
|
|* PRD-01 CP  : /, /tentang-kami, /ulasan-kolaborasi, /galeri, /kontak

| PRD-02 CAT : /produk, /produk/kategori/{slug}, /produk/{slug}

|* PRD-03 ORD : /keranjang, /checkout, /checkout/ringkasan, /pesanan/berhasil/{kode},
|              /custom-order, /custom-order/berhasil/{kode}
| Larangan   : TIDAK ADA route cek status pesanan (ORD-BR-013).
*/

use App\Http\Controllers\PublicSite\Catalog\CatalogController;
use App\Http\Controllers\PublicSite\Catalog\PriceEstimateController;
use App\Http\Controllers\PublicSite\Catalog\ProductController;
use App\Http\Controllers\PublicSite\CompanyProfile\AboutController;
use App\Http\Controllers\PublicSite\CompanyProfile\ContactController;
use App\Http\Controllers\PublicSite\CompanyProfile\GalleryController;
use App\Http\Controllers\PublicSite\CompanyProfile\HomeController;
use App\Http\Controllers\PublicSite\CompanyProfile\ReviewController;
use Illuminate\Support\Facades\Route;

// PRD-01 CP
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('about');
Route::get('/ulasan-kolaborasi', [ReviewController::class, 'index'])->name('reviews');
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');

// PRD-02 CAT
Route::get('/produk', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produk/kategori/{category:slug}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/produk/{product:slug}/estimasi', PriceEstimateController::class)->name('products.estimate');
Route::get('/produk', [\App\Http\Controllers\PublicSite\Catalog\CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produk/kategori/{category:slug}', [\App\Http\Controllers\PublicSite\Catalog\CatalogController::class, 'category'])->name('catalog.category');
Route::get('/produk/{product:slug}', [\App\Http\Controllers\PublicSite\Catalog\ProductController::class, 'show'])->name('products.show');
Route::post('/produk/{product:slug}/estimasi', \App\Http\Controllers\PublicSite\Catalog\PriceEstimateController::class)->name('products.estimate');

// PRD-03 ORD
Route::post('/lampiran', [\App\Http\Controllers\PublicSite\Ordering\AttachmentController::class, 'store'])->middleware('throttle:30,1')->name('attachments.store');
Route::delete('/lampiran/{attachment}', [\App\Http\Controllers\PublicSite\Ordering\AttachmentController::class, 'destroy'])->name('attachments.destroy');
Route::get('/custom-order', [\App\Http\Controllers\PublicSite\Ordering\CustomOrderController::class, 'create'])->name('custom-orders.create');
Route::post('/custom-order', [\App\Http\Controllers\PublicSite\Ordering\CustomOrderController::class, 'store'])->middleware('throttle:5,10')->name('custom-orders.store');
Route::get('/custom-order/berhasil/{code}', [\App\Http\Controllers\PublicSite\Ordering\CustomOrderController::class, 'success'])->name('custom-orders.success');
