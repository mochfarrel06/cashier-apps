<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cashier\Dashboard\DashboardController;
use App\Http\Controllers\Cashier\Report\IncomeReportController;
use App\Http\Controllers\Cashier\Report\TransactionReportController;
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
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Transaction
    Route::get('transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('transaction/add-to-cart', [TransactionController::class, 'addToCart'])->name('transaction.addToCart');
    Route::post('transaction/checkout', [TransactionController::class, 'checkout'])->name('transaction.checkout');
    Route::post('transaction/remove-from-cart/{id}', [TransactionController::class, 'removeFromCart'])->name('transaction.removeFromCart');
    Route::get('transaction/receipt/{id}', [TransactionController::class, 'receipt'])->name('transaction.receipt');
    Route::get('transaction/{id}/pdf', [TransactionController::class, 'generatePDF'])->name('transaction.pdf');

    // Report transaction
    Route::get('transaction-report', [TransactionReportController::class, 'index'])->name('transaction-report.index');
    Route::get('transaction-report/{id}', [TransactionReportController::class, 'show'])->name('transaction-report.show');
    Route::get('transaction-report/{id}/pdf', [TransactionReportController::class, 'generatePDF'])->name('transaction-report.pdf');
    Route::get('transaction-report/report/exportExcel', [TransactionReportController::class, 'exportExcel'])->name('transaction-report.exportExcel');

    // Income Report
    Route::get('income-report', [IncomeReportController::class, 'index'])->name('income-report.index');
    Route::get('income-report/download', [IncomeReportController::class, 'exportPdf'])->name('income-report.download');
});
