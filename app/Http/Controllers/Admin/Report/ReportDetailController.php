<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportDetailController extends Controller
{
    public function getFilteredData($startDate, $endDate)
    {
        // Inisialisasi query
        $query = TransactionDetail::query();

        // Filter berdasarkan tanggal
        if ($startDate && $endDate) {
            $endDate = Carbon::parse($endDate)->addDay()->format('Y-m-d');
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Mengambil data hasil filter
        return $query->with('cashierProduct.product')->get();
    }

    public function index(Request $request){
        // Mengambil input filter dari request
        $cashierId = $request->input('cashier_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mengambil data kasir untuk ditampilkan di dropdown
        // $users = User::where('role', 'cashier')->get();

        // Jika tidak ada filter, set transactions sebagai koleksi kosong
        $transactionDetails = collect();

        // Jika ada filter, baru jalankan query untuk mendapatkan transaksi
        if ($startDate || $endDate) {
            $transactionDetails = $this->getFilteredData($startDate, $endDate);
        }

        return view('admin.report.report-detail.index', compact('transactionDetails', 'startDate', 'endDate'));
    }
}
