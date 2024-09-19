<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;

class ReportProductController extends Controller
{
    public function index(){
        $transactionDetails = TransactionDetail::all();

        return view('admin.report.report-product.index', compact('transactionDetails'));
    }
}
