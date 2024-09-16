<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CashierProduct;
use App\Models\Flavor;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $cashierCount = User::where('role', 'cashier')->get()->count();
        $productCount = Product::all()->count();
        $flavorCount = Flavor::all()->count();

        $cards = [
            [
                'bg_color' => 'primary',
                'icon' => 'far fas fa-solid fa-box',
                'title' => 'Jumlah Produk',
                'value' => $productCount,
            ],
            [
                'bg_color' => 'warning',
                'icon' => 'far fas fa-regular fa-lemon',
                'title' => 'Varian Produk',
                'value' => $flavorCount,
            ],
            [
                'bg_color' => 'info',
                'icon' => 'far fas fa-user',
                'title' => 'Pengguna Kasir',
                'value' => $cashierCount,
            ]
        ];

        $cashierProducts = CashierProduct::with(['user', 'product', 'flavor'])->get();

        return view('admin.dashboard.index', compact('cards', 'cashierProducts'));
    }
}
