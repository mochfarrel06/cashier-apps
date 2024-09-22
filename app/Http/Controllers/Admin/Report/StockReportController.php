<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\StockExport;
use App\Http\Controllers\Controller;
use App\Models\StockReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StockReportController extends Controller
{
    public function getFilteredData($startDate, $endDate, $cashierId = null)
    {
        // Inisialisasi query
        $query = StockReport::with(['cashierProduct.user', 'cashierProduct.product', 'cashierProduct.flavor']);

        // Filter berdasarkan tanggal
        if ($startDate && $endDate) {
            // Menggunakan filter tanggal secara langsung tanpa menambah hari
            $query->whereDate('stock_date', '>=', $startDate)
                ->whereDate('stock_date', '<=', $endDate);
        }

        // Filter berdasarkan kasir jika diberikan
        if ($cashierId) {
            $query->whereHas('cashierProduct.user', function ($q) use ($cashierId) {
                $q->where('id', $cashierId);
            });
        }

        // Mengambil data hasil filter
        return $query->orderBy('stock_date', 'desc')->get();
    }

    public function index(Request $request)
    {
        // Mengambil input filter dari request
        $cashierId = $request->input('cashier_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mengambil data kasir untuk dropdown
        $users = User::where('role', 'cashier')->get();

        // Jika tidak ada filter, set stockReports sebagai koleksi kosong
        $stockReports = collect();

        // Jika ada filter, ambil data sesuai dengan filter
        if ($cashierId || $startDate || $endDate) {
            $stockReports = $this->getFilteredData($startDate, $endDate, $cashierId);
        }

        // Menampilkan view dengan data yang telah difilter
        return view('admin.report.stock-report.index', compact('stockReports', 'users', 'cashierId', 'startDate', 'endDate'));
    }

    public function exportExcel(Request $request)
    {
        $cashierId = $request->input('cashier_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ambil data laporan stok yang difilter
        $stockReports = collect();

        if ($cashierId || $startDate || $endDate) {
            $stockReports = $this->getFilteredData($startDate, $endDate, $cashierId);
        }

        // Download data laporan stok dalam format Excel
        return Excel::download(new StockExport($stockReports, $startDate, $endDate), 'laporan-stok.xlsx');
    }
}
