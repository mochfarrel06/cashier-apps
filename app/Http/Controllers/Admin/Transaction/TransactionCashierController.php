<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionCashierController extends Controller
{
    public function index()
    {
        // Ambil transaksi hari ini untuk kasir yang sedang login
        $transactions = Transaction::with('transactionDetails.cashierProduct.product')->get();

        return view('admin.transaction-cashier.index', compact('transactions'));
    }

    public function show(string $id)
    {
        // Mengambil transaksi berdasarkan ID
        $transaction = Transaction::with('transactionDetails.cashierProduct.product', 'user')->findOrFail($id);

        // Menampilkan view dengan data detail transaksi
        return view('admin.transaction-cashier.show', compact('transaction'));
    }
}
