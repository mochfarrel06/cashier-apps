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
        $stockReports = StockReport::with('product', 'flavor')
            ->where('user_id', auth()->user()->id)
            ->orderBy('report_date', 'desc')
            ->get();

        return view('admin.report.stock-report.index', compact('stockReports'));
    }

    public function generateReport()
    {
        // Ambil semua produk yang ada di kasir untuk stok laporan harian
        $cashierProducts = CashierProduct::with('product', 'flavor')
            ->where('user_id', auth()->user()->id)
            ->get();

        foreach ($cashierProducts as $cashierProduct) {
            // Simpan laporan stok harian
            StockReport::updateOrCreate(
                [
                    'user_id' => $cashierProduct->user_id,
                    'product_id' => $cashierProduct->product_id,
                    'flavor_id' => $cashierProduct->flavor_id,
                    'report_date' => Carbon::now()->format('Y-m-d'),
                ],
                [
                    'stock' => $cashierProduct->stock,
                ]
            );
        }

        return redirect()->route('admin.stock-report.index')->with('success', 'Laporan stok harian berhasil dibuat.');
    }
}
