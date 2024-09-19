<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\SalesReport;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function index()
    {
        // Ambil semua laporan penjualan
        $salesReports = SalesReport::with('user')->orderBy('report_date', 'desc')->get();

        // Kirim data ke view
        return view('admin.report.sales-report.index', compact('salesReports'));
    }

    public function generateReport(Request $request)
    {
        // Validasi input
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->input('date'))->format('Y-m-d');

        // Ambil data transaksi berdasarkan tanggal
        $transactions = Transaction::whereDate('transaction_date', $date)->get();

        // Kelompokkan transaksi berdasarkan kasir
        $groupedTransactions = $transactions->groupBy('user_id');

        // Hitung total penjualan untuk setiap kasir
        foreach ($groupedTransactions as $userId => $transactions) {
            $totalSales = $transactions->sum('total');

            // Simpan laporan penjualan
            SalesReport::updateOrCreate(
                ['user_id' => $userId, 'report_date' => $date],
                ['total_sales' => $totalSales]
            );
        }

        return response()->json(['message' => 'Sales reports generated successfully.']);
    }

    public function showReport($date)
    {
        // Ambil laporan berdasarkan tanggal
        $reports = SalesReport::whereDate('report_date', $date)
            ->with('user')
            ->get();

        return response()->json($reports);
    }
}
