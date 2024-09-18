<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CashierProduct;
use App\Models\Flavor;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getFilteredData($cashierId = null)
    {
        $query = CashierProduct::query();

        if ($cashierId) {
            $query->where('user_id', $cashierId);
        }

        return $query->with('product')->get();
    }

    public function index(Request $request)
    {
        $cashierCount = User::where('role', 'cashier')->get()->count();
        $productCount = Product::all()->count();
        $flavorCount = Flavor::all()->count();

        $cashierId = $request->input('cashier_id');

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

        $users = User::where('role', 'cashier')->get();

        $cashierProducts = collect();
        if ($cashierId) {
            $cashierProducts = $this->getFilteredData($cashierId);
        }

        return view('admin.dashboard.index', compact('cards', 'cashierProducts', 'users', 'cashierId'));
    }
}
