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

// PRD-03 ORD