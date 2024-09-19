<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionCashierController extends Controller
{
    public function getFilteredData($startDate, $endDate, $cashierId = null)
    {
        // Inisialisasi query
        $query = Transaction::query();

        // Filter berdasarkan tanggal
        if ($startDate && $endDate) {
            $endDate = Carbon::parse($endDate)->addDay()->format('Y-m-d');
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Filter berdasarkan kasir jika diberikan
        if ($cashierId) {
            $query->where('user_id', $cashierId); // Asumsi 'user_id' adalah ID kasir pada transaksi
        }

        // Mengambil data hasil filter
        return $query->with('transactionDetails.cashierProduct.product')->get();
    }

    public function index(Request $request)
    {
        $cashierId = $request->input('cashier_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mengambil data kasir untuk ditampilkan di dropdown
        $users = User::where('role', 'cashier')->get();

        // Jika tidak ada filter, set transactions sebagai koleksi kosong
        $transactions = collect();

        // Jika ada filter, baru jalankan query untuk mendapatkan transaksi
        if ($cashierId || $startDate || $endDate) {
            $transactions = $this->getFilteredData($startDate, $endDate, $cashierId);
        }

        return view('admin.transaction-cashier.index', compact('transactions', 'users', 'cashierId', 'startDate', 'endDate'));
    }

    public function show(string $id)
    {
        $transaction = Transaction::with('transactionDetails.cashierProduct.product', 'user')->findOrFail($id);

        return view('admin.transaction-cashier.show', compact('transaction'));
    }

    public function exportPdf(string $id){
        // Ambil transaksi beserta detailnya
        $transaction = Transaction::with('transactionDetails.cashierProduct.product', 'transactionDetails.cashierProduct.flavor')->findOrFail($id);

        // Generate PDF menggunakan view receipt
        $pdf = Pdf::loadView('receipt.receipt_pdf', compact('transaction'));

        // Download PDF
        return $pdf->download('Nota-' . $transaction->transaction_number . '.pdf');
    }
}
