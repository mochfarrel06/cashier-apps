<?php

use App\Http\Controllers\Admin\CashierProduct\CashierProductController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Flavor\FlavorController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Report\ReportDetailController;
use App\Http\Controllers\Admin\Report\ReportTransactionController;
use App\Http\Controllers\Auth\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'role:admin'], function () {
    // Route Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk profile pengguna
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit-profile', [ProfileController::class, 'editProfile'])->name('profile.editProfile');
    Route::put('profile/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');

    // Route untuk password
    Route::get('profile/edit-password', [ProfileController::class, 'editPassword'])->name('profile.editPassword');
    Route::put('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // Route Product
    Route::resource('product', ProductController::class);

    // Route Flavor
    Route::resource('flavor', FlavorController::class);

    // Route Cashier Product
    Route::resource('cashier-product', CashierProductController::class);
    Route::get('products/{product_id}/flavors', [CashierProductController::class, 'getFlavorsByProduct'])->name('products.flavors');

    // Report Transaction
    Route::get('report-transaction', [ReportTransactionController::class, 'index'])->name('report-transaction.index');
    Route::get('report-transaction/export-excel', [ReportTransactionController::class, 'exportExcel'])->name('report-transaction.exportExcel');

    // Report detail transaction
    Route::get('report-detail', [ReportDetailController::class, 'index'])->name('report-detail.index');
    Route::get('report-detail/export-excel', [ReportDetailController::class, 'exportExcel'])->name('report-detail.exportExcel');
});
