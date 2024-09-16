<?php

namespace App\Http\Controllers\Cashier\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Traits\PdfReportDailyTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportDetailController extends Controller
{
    use PdfReportDailyTrait;

    public function dailyReport(Request $request)
    {
        // Ambil transaksi hari ini untuk kasir yang sedang login
        $transactions = Transaction::with('transactionDetails.cashierProduct.product')
            ->where('user_id', auth()->user()->id)
            ->whereDate('transaction_date', now())
            ->get();

        return view('cashier.detail-report.index', compact('transactions'));
    }

    public function showReportDetail($id)
    {
        // Ambil transaksi beserta detailnya
        $transaction = Transaction::with('transactionDetails.cashierProduct.product')
            ->where('user_id', auth()->user()->id)
            ->findOrFail($id);

        return view('cashier.detail-report.show', compact('transaction'));
    }

    public function downloadDailyReportPdf($id)
    {
        // Ambil transaksi beserta detailnya
        $transaction = Transaction::with('transactionDetails.cashierProduct.product', 'transactionDetails.cashierProduct.flavor')->findOrFail($id);

        // Generate and download PDF using the trait method
        return $this->generatePdf($transaction, 'cashier.transaction.receipt_pdf', 'daily-report-transaction-' . $transaction->transaction_number . '.pdf');
    }

    public function downloadAllDailyReport()
    {
        // Ambil transaksi untuk hari ini
        $transactions = Transaction::with('transactionDetails.cashierProduct.product')
            ->whereDate('created_at', Carbon::today())
            ->where('user_id', auth()->user()->id) // Khusus untuk kasir yang login
            ->get();

        // Generate PDF menggunakan view yang sama atau view khusus PDF
        $pdf = Pdf::loadView('cashier.detail-report.report-all', compact('transactions'));

        // Download PDF
        return $pdf->download('daily-report-' . Carbon::today()->format('Y-m-d') . '.pdf');
    }
}
