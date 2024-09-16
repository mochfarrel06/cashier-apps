<?php

namespace App\Http\Controllers\Cashier\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportIncomeController extends Controller
{
    // Menampilkan pendapatan harian kasir di browser
    public function dailyIncome()
    {
        // Ambil transaksi untuk hari ini yang dilakukan oleh kasir yang sedang login
        $transactions = Transaction::whereDate('created_at', Carbon::today())
            ->where('user_id', auth()->user()->id) // Ambil transaksi milik kasir yang login
            ->get();

        // Hitung total pendapatan
        $totalIncome = $transactions->sum('total');

        return view('cashier.income-report.index', compact('transactions', 'totalIncome'));
    }

    // Mendownload pendapatan harian kasir dalam bentuk PDF
    public function downloadDailyIncome()
    {
        // Ambil transaksi untuk hari ini yang dilakukan oleh kasir yang sedang login
        $transactions = Transaction::whereDate('created_at', Carbon::today())
            ->where('user_id', auth()->user()->id) // Ambil transaksi milik kasir yang login
            ->get();

        // Hitung total pendapatan
        $totalIncome = $transactions->sum('total');

        // Generate PDF menggunakan view yang berisi laporan pendapatan
        $pdf = Pdf::loadView('cashier.income-report.income_pdf', compact('transactions', 'totalIncome'));

        // Download PDF
        return $pdf->download('daily-income-' . Carbon::today()->format('Y-m-d') . '.pdf');
    }
}
