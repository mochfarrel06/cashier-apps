<?php

namespace App\Http\Controllers\Cashier\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(){
        $transactions = Transaction::with(['user']);
        return view('cashier.transaction.index');
    }
}
