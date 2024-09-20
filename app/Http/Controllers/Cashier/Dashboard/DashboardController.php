<?php

namespace App\Http\Controllers\Cashier\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CashierProduct;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua produk yang ditambahkan ke kasir berdasarkan user_id
        $cashierProducts = CashierProduct::where('user_id', auth()->user()->id)->get();

        // Hitung jumlah produk
        $totalProducts = $cashierProducts->unique('product_id')->count();

        // Hitung jumlah varian rasa
        $totalFlavors = $cashierProducts->groupBy('flavor_id')->count();

        // Ambil transaksi untuk hari ini yang dilakukan oleh kasir yang sedang login
        $transactions = Transaction::whereDate('created_at', Carbon::today())
            ->where('user_id', auth()->user()->id) // Ambil transaksi milik kasir yang login
            ->get();

        // Hitung total pendapatan
        $totalIncome = $transactions->sum('total');
        $formattedIncome = 'Rp ' . number_format($totalIncome, 0, ',', '.');

        $cards = [
            [
                'bg_color' => 'primary',
                'icon' => 'far fas fa-solid fa-box',
                'title' => 'Jumlah Produk',
                'value' => $totalProducts,
            ],
            [
                'bg_color' => 'warning',
                'icon' => 'far fas fa-regular fa-lemon',
                'title' => 'Varian Produk',
                'value' => $totalFlavors,
            ],
            [
                'bg_color' => 'info',
                'icon' => 'far fas fa-regular fa-money-bill',
                'title' => 'Pendapatan Hari ini',
                'value' => $formattedIncome,
            ],
        ];

        return view('cashier.dashboard.index', compact('cards', 'cashierProducts'));
    }
}
