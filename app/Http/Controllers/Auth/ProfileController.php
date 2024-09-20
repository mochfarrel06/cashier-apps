<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        $user = auth()->user();

        if ($user->role == 'admin') {
            return view('admin.profile.index');
        } elseif ($user->role == 'cashier') {
            return view('cashier.profile.index');
        }
    }
}
