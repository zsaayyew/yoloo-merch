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

use App\Http\Controllers\Admin\Auth\LoginController;
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
    });
});

// PRD-04 ADM - dashboard, konten, katalog, harga, media, user (Owner)

// PRD-05 FIN - order, pelanggan, pembayaran, refund, invoice, pengeluaran, laporan, QRIS
