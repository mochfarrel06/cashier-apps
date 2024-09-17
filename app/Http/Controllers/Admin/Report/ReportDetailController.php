<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportDetailController extends Controller
{
    public function detailReport(){
        $transactions = Transaction::with('transactionDetails.cashierProduct.product')->get();
        return view('admin.report.report-detail.index', compact('transactions'));
    }
}
