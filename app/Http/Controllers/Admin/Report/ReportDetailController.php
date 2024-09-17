<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportDetailController extends Controller
{
    public function getFilteredData($startDate, $endDate, $cashierId = null)
    {
        // Inisialisasi query
        $query = Transaction::query();

        // Filter berdasarkan tanggal
        if ($startDate && $endDate) {
            $endDate = Carbon::parse($endDate)->addDay()->format('Y-m-d');
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Filter berdasarkan kasir jika diberikan
        if ($cashierId) {
            $query->where('user_id', $cashierId); // Asumsi 'user_id' adalah ID kasir pada transaksi
        }

        // Mengambil data hasil filter
        return $query->with('transactionDetails.cashierProduct.product')->get();
    }


    public function detailReport(Request $request)
    {
        // Mengambil input filter dari request
        $cashierId = $request->input('cashier_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mengambil data kasir untuk ditampilkan di dropdown
        $users = User::where('role', 'cashier')->get();

        // Mengambil transaksi berdasarkan filter yang diterapkan
        $transactions = $this->getFilteredData($startDate, $endDate, $cashierId);

        // Menampilkan view dengan data yang difilter
        return view('admin.report.report-detail.index', compact('transactions', 'users', 'cashierId', 'startDate', 'endDate'));
    }
}
