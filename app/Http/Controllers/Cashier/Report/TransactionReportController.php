<?php

namespace App\Http\Controllers\Cashier\Report;

use App\Exports\CashierTransactionExport;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransactionReportController extends Controller
{
    public function index()
    {
        // Ambil transaksi hari ini untuk kasir yang sedang login
        $transactions = Transaction::with('transactionDetails.cashierProduct.product')
            ->where('user_id', auth()->user()->id)
            ->whereDate('transaction_date', now())
            ->get();

        return view('cashier.report.transaction-report.index', compact('transactions'));
    }

    public function show($id)
    {
        // Ambil transaksi beserta detailnya
        $transaction = Transaction::with('transactionDetails.cashierProduct.product')
            ->where('user_id', auth()->user()->id)
            ->findOrFail($id);

        return view('cashier.report.transaction-report.show', compact('transaction'));
    }

    public function generatePDF($id)
    {
        // Ambil transaksi beserta detailnya
        $transaction = Transaction::with('transactionDetails.cashierProduct.product', 'transactionDetails.cashierProduct.flavor')->findOrFail($id);

        // Generate PDF menggunakan view receipt
        $pdf = Pdf::loadView('receipt.receipt_pdf', compact('transaction'));

        // Download PDF
        return $pdf->download('Nota-' . $transaction->transaction_number . '.pdf');
    }

    public function exportExcel()
    {
        // Ambil transaksi hari ini untuk kasir yang sedang login
        $transactions = Transaction::with('transactionDetails.cashierProduct.product')
            ->where('user_id', auth()->user()->id)
            ->whereDate('transaction_date', now())
            ->get();

        // Download data transaksi dalam format Excel
        return Excel::download(new CashierTransactionExport($transactions), 'laporan-transaksi-harian.xlsx');
    }
}
