<?php

namespace App\Http\Controllers\Cashier\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('cashier.dashboard.index');
    }
}
