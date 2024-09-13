<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\AuthRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(AuthRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        if ($request->user()->role == 'admin') {
            session()->flash('success', 'Berhasil masuk halaman dashbaord Admin');
            return redirect()->intended(RouteServiceProvider::ADMIN);
        }else if ($request->user()->role == 'kasir') {
            session()->flash('success', 'Berhasil masuk halaman dashbaord Kasir');
            return redirect()->intended(RouteServiceProvider::CASHIER);
        }else {
            Auth::logout();
            session()->flash('error', "Anda tidak memiliki akses untuk masuk ke aplikasi");
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->flash('success', 'Berhasil keluar aplikasi');
        return redirect('/login');
    }
}
