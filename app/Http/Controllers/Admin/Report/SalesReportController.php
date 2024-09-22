<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\SalesExport;
use App\Http\Controllers\Controller;
use App\Models\SalesReport;
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
            // Menggunakan filter tanggal secara langsung tanpa menambah hari
            $query->whereDate('report_date', '>=', $startDate)
                ->whereDate('report_date', '<=', $endDate);
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
        return Excel::download(new SalesExport($transactions, $startDate, $endDate), 'laporan-penjualan.xlsx');
    }
}
