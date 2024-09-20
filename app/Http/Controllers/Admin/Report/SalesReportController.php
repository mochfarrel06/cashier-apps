<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\SalesExport;
use App\Http\Controllers\Controller;
use App\Models\SalesReport;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
    public function getFilteredData($startDate, $endDate, $cashierId = null)
    {
        // Inisialisasi query
        $query = SalesReport::query();

        // Filter berdasarkan tanggal
        if ($startDate && $endDate) {
            // Menambah satu hari ke endDate untuk menyertakan seluruh hari tersebut
            $endDate = Carbon::parse($endDate)->addDay()->format('Y-m-d');
            $query->whereBetween('report_date', [$startDate, $endDate]);
        }

        // Filter berdasarkan kasir jika diberikan
        if ($cashierId) {
            $query->where('user_id', $cashierId); // Asumsi 'user_id' adalah ID kasir pada laporan penjualan
        }

        // Mengambil data hasil filter dengan relasi ke user
        return $query->with('user')->orderBy('report_date', 'desc')->get();
    }

    public function index(Request $request)
    {
        // Mengambil input filter dari request
        $cashierId = $request->input('cashier_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mengambil data kasir untuk dropdown
        $users = User::where('role', 'cashier')->get();

        // Jika tidak ada filter, set salesReports sebagai koleksi kosong
        $salesReports = collect();

        // Jika ada filter, ambil data sesuai dengan filter
        if ($cashierId || $startDate || $endDate) {
            $salesReports = $this->getFilteredData($startDate, $endDate, $cashierId);
        }
        // else {
        //     // Jika tidak ada filter, ambil semua laporan
        //     $salesReports = SalesReport::with('user')->orderBy('report_date', 'desc')->get();
        // }

        // Menampilkan view dengan data yang telah difilter
        return view('admin.report.sales-report.index', compact('salesReports', 'users', 'cashierId', 'startDate', 'endDate'));
    }

    public function exportExcel(Request $request)
    {
        $cashierId = $request->input('cashier_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ambil data transaksi yang difilter
        $transactions = collect();

        if ($cashierId || $startDate || $endDate) {
            $transactions = $this->getFilteredData($startDate, $endDate, $cashierId);
        }

        // Download data transaksi dalam format Excel
        return Excel::download(new SalesExport($transactions, $startDate, $endDate), 'laporan-transaksi.xlsx');
    }

    // public function generateReport(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'date' => 'required|date',
    //     ]);

    //     $date = Carbon::parse($request->input('date'))->format('Y-m-d');

    //     // Ambil data transaksi berdasarkan tanggal
    //     $transactions = Transaction::whereDate('transaction_date', $date)->get();

    //     // Kelompokkan transaksi berdasarkan kasir
    //     $groupedTransactions = $transactions->groupBy('user_id');

    //     // Hitung total penjualan untuk setiap kasir
    //     foreach ($groupedTransactions as $userId => $transactions) {
    //         $totalSales = $transactions->sum('total');

    //         // Simpan laporan penjualan
    //         SalesReport::updateOrCreate(
    //             ['user_id' => $userId, 'report_date' => $date],
    //             ['total_sales' => $totalSales]
    //         );
    //     }

    //     return response()->json(['message' => 'Sales reports generated successfully.']);
    // }

    // public function showReport($date)
    // {
    //     // Ambil laporan berdasarkan tanggal
    //     $reports = SalesReport::whereDate('report_date', $date)
    //         ->with('user')
    //         ->get();

    //     return response()->json($reports);
    // }
}
