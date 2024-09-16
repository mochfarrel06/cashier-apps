<?php

// use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cashier\Dashboard\DashboardController;
use App\Http\Controllers\Cashier\Report\ReportDetailController;
use App\Http\Controllers\Cashier\Transaction\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('store');
Route::post('logout', [LoginController::class, 'destroy'])->name('destroy');


Route::group(['prefix' => 'cashier', 'as' => 'cashier.', 'middleware' => 'role:cashier'], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('transaction/add-to-cart', [TransactionController::class, 'addToCart'])->name('transaction.addToCart');
    Route::post('transaction/checkout', [TransactionController::class, 'checkout'])->name('transaction.checkout');
    Route::post('transaction/remove-from-cart/{id}', [TransactionController::class, 'removeFromCart'])->name('transaction.removeFromCart');

    Route::get('transaction/receipt/{id}', [TransactionController::class, 'receipt'])->name('transaction.receipt');

    Route::get('transaction/{id}/pdf', [TransactionController::class, 'generatePDF'])->name('transaction.pdf');

    // Route
    Route::get('report/daily-report', [ReportDetailController::class, 'dailyReport'])->name('report.dailyReport');
    Route::get('report/{id}/detail', [ReportDetailController::class, 'showReportDetail'])->name('report.showReportDetail');
    Route::get('report/daily/download', [ReportDetailController::class, 'downloadAllDailyReport'])->name('report.downloadAll');
});
