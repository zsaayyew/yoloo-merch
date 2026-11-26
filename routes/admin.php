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
use App\Http\Controllers\Admin\Sales\CustomerController;
use App\Http\Controllers\Admin\Sales\OrderController;
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

        // PRD-05 FIN - daftar & detail order/request (FIN-FR-001, FIN-FR-002)
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])
            ->whereNumber('order')->name('orders.show');
        Route::get('requests/{orderRequest}', [OrderController::class, 'showRequest'])
            ->whereNumber('orderRequest')->name('requests.show');

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

// PRD-04 ADM - dashboard, konten, katalog, harga, media, user (Owner)

// PRD-05 FIN - (sisa) status, konversi, pembayaran, refund, invoice, pengeluaran, laporan, QRIS
