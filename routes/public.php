<?php

/*
|--------------------------------------------------------------------------
| Route Public Website
|--------------------------------------------------------------------------
| Di-load dari routes/web.php dengan: require __DIR__.'/public.php';
| Kelompokkan per domain dengan komentar di bawah. Nama route TANPA prefix.
| Daftar route lengkap ada di dokumen rancangan bagian 4.5.
|
| PRD-01 CP  : /, /tentang-kami, /ulasan-kolaborasi, /galeri, /kontak
| PRD-02 CAT : /produk, /produk/kategori/{slug}, /produk/{slug}
| PRD-03 ORD : /keranjang, /checkout, /checkout/ringkasan, /pesanan/berhasil/{kode},
|              /custom-order, /custom-order/berhasil/{kode}
| Larangan   : TIDAK ADA route cek status pesanan (ORD-BR-013).
*/

use Illuminate\Support\Facades\Route;

// PRD-01 CP

// PRD-02 CAT
Route::get('/produk', [\App\Http\Controllers\PublicSite\Catalog\CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produk/kategori/{category:slug}', [\App\Http\Controllers\PublicSite\Catalog\CatalogController::class, 'category'])->name('catalog.category');
Route::get('/produk/{product:slug}', [\App\Http\Controllers\PublicSite\Catalog\ProductController::class, 'show'])->name('products.show');
Route::post('/produk/{product:slug}/estimasi', \App\Http\Controllers\PublicSite\Catalog\PriceEstimateController::class)->name('products.estimate');
// PRD-03 ORD