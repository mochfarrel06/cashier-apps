<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\DetailsExport;
use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportDetailController extends Controller
{
    public function getFilteredData($startDate, $endDate, $cashierId = null)
    {
        // Inisialisasi query
        $query = TransactionDetail::query();

        $query->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')->join('users', 'transactions.user_id', '=', 'users.id');

        if ($cashierId) {
            $query->where('users.id', $cashierId); // Asumsi 'user_id' adalah ID kasir pada transaksi
        }

        // Filter berdasarkan tanggal
        if ($startDate && $endDate) {
            $endDate = Carbon::parse($endDate)->addDay()->format('Y-m-d');
            $query->whereBetween('transaction_details.created_at', [$startDate, $endDate]);
        }

        // Mengambil data hasil filter
        return $query->with('cashierProduct.product')->get();
    }

    public function index(Request $request){
        // Mengambil input filter dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $cashierId = $request->input('cashier_id');

        $cashiers = User::where('role', 'cashier')->get();

        // Jika tidak ada filter, set transactions sebagai koleksi kosong
        $transactionDetails = collect();

        // Jika ada filter, baru jalankan query untuk mendapatkan transaksi
        if ($startDate || $endDate || $cashierId) {
            $transactionDetails = $this->getFilteredData($startDate, $endDate, $cashierId);
        }

        return view('admin.report.report-detail.index', compact('transactionDetails', 'startDate', 'endDate', 'cashierId', 'cashiers'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $cashierId = $request->input('cashier_id');

        // Ambil data transaksi yang difilter
        $transactionDetails = collect();

        // Jika ada filter, baru jalankan query untuk mendapatkan transaksi
        if ($startDate || $endDate || $cashierId) {
            $transactionDetails = $this->getFilteredData($startDate, $endDate, $cashierId);
        }

        // Download data transaksi dalam format Excel
        return Excel::download(new DetailsExport($transactionDetails, $startDate, $endDate), 'laporan-detail-transaksi.xlsx');
    }
}
