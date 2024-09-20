<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\CashierProduct;
use App\Models\StockReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index()
    {
        // Ambil laporan stok berdasarkan user yang sedang login (role kasir)
        $stockReports = StockReport::with(['cashierProduct.user', 'cashierProduct.product', 'cashierProduct.flavor'])
        ->whereDate('stock_date', now()->format('Y-m-d'))
        ->get();

        return view('admin.report.stock-report.index', compact('stockReports'));
    }
}
