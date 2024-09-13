<?php

namespace App\Http\Controllers\Admin\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Requests\Cart\CartUpdateRequest;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::with('user')->get();
        return view('admin.cart.index', compact('carts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::whereDoesntHave('cart')->where('role', 'cashier')->get();
        return view('admin.cart.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartStoreRequest $request)
    {
        try {
            $user = User::findOrFail($request->user_id);

            $cart = new Cart([
                'user_id' => $user->id,
                'name' => $request->name,
                'location' => $request->location
            ]);

            $cart->save();

            session()->flash('success', 'Berhasil menambahkan data kasir');
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', 'Terdapat kesalahan pada proses data kasir: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cart = Cart::findOrFail($id);
        $user = $cart->user;

        return view('admin.cart.show', compact('cart', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cart = Cart::findOrFail($id);
        $users = User::where('role', 'cashier')->get();

        return view('admin.cart.edit', compact('cart', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartUpdateRequest $request, string $id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $user = User::findOrFail($request->user_id);

            $carts = $request->all();
            $carts['user_id'] = $user->id;

            $cart->fill($carts);

            if ($cart->isDirty()) {
                $cart->save();

                session()->flash('success', 'Berhasil melakukan perubahan pada data kasir');
                return response()->json(['success' => true], 200);
            } else {
                session()->flash('info', 'Tidak melakukan perubahan pada data kasir');
                return response()->json(['info' => true], 200);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terdapat kesalahan pada data kasir: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $cart->delete();

            return response(['status' => 'success', 'message' => 'Berhasil menghapus data kasir']);
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan saat menghapus
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
