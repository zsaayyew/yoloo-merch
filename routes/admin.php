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

use Illuminate\Support\Facades\Route;

// PRD-04 ADM - login (guest)

// PRD-04 ADM - dashboard, konten, katalog, harga, media, user (Owner)

// PRD-05 FIN - order, pelanggan, pembayaran, refund, invoice, pengeluaran, laporan, QRIS