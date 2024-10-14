<?php

namespace App\Http\Controllers\Cashier\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class IncomeReportController extends Controller
{
    public function index()
    {
        // Ambil transaksi untuk hari ini yang dilakukan oleh kasir yang sedang login
        $transactions = Transaction::whereDate('created_at', Carbon::today())
            ->where('user_id', auth()->user()->id) // Ambil transaksi milik kasir yang login
            ->get();

        // Hitung total pendapatan
        $totalIncome = $transactions->sum('net_total');

        return view('cashier.report.income-report.index', compact('transactions', 'totalIncome'));
    }

    public function exportPdf()
    {
        // Ambil transaksi untuk hari ini yang dilakukan oleh kasir yang sedang login
        $transactions = Transaction::whereDate('created_at', Carbon::today())
            ->where('user_id', auth()->user()->id) // Ambil transaksi milik kasir yang login
            ->get();

        // Hitung total pendapatan
        $totalIncome = $transactions->sum('net_total');

        // Generate PDF menggunakan view yang berisi laporan pendapatan
        $pdf = Pdf::loadView('cashier.report.income-report.income_pdf', compact('transactions', 'totalIncome'));

        // Download PDF
        return $pdf->download('Laporan Pendapatan -' . Carbon::today()->format('Y-m-d') . '.pdf');
    }
}
