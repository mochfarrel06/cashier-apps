<?php

namespace App\Http\Controllers\Admin\CashierProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashierProduct\CashierProductStoreRequest;
use App\Http\Requests\CashierProduct\CashierProductUpdateRequest;
use App\Models\CashierProduct;
use App\Models\Flavor;
use App\Models\Product;
use App\Models\User;

class CashierProductController extends Controller
{
    public function getFlavorsByProduct($product_id)
    {
        $flavors = Flavor::where('product_id', $product_id)->get();
        return response()->json($flavors);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cashierProducts = CashierProduct::with(['user', 'product', 'flavor'])->get();
        return view('admin.cashier-product.index', compact('cashierProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'cashier')->get();
        $products = Product::all();

        return view('admin.cashier-product.create', compact('users', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CashierProductStoreRequest $request)
    {
        try {
            $user = User::findOrFail($request->user_id);
            $product = Product::findOrFail($request->product_id);
            $flavor = Flavor::findOrFail($request->flavor_id);

            // Cek apakah kombinasi kasir, produk, dan varian rasa sudah ada
            $existing = CashierProduct::where('user_id', $request->user_id)
                ->where('product_id', $request->product_id)
                ->where('flavor_id', $request->flavor_id)
                ->first();

            if ($existing) {
                session()->flash('error', 'Varian rasa sudah di tambahkan');
                return response()->json(['error' => true], 400);
            }

            $cashierProduct = new CashierProduct([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'flavor_id' => $flavor->id,
                'stock' => $request->stock
            ]);

            $cashierProduct->save();

            session()->flash('success', 'Berhasil menambahkan produk untuk kasir');
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', 'Terdapat kesalahan pada proses produk untuk kasir: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cashierProduct = CashierProduct::findOrFail($id);
        $user = $cashierProduct->user;
        $product = $cashierProduct->product;
        $flavor = $cashierProduct->flavor;

        return view('admin.cashier-product.show', compact('cashierProduct', 'user', 'product', 'flavor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cashierProduct = CashierProduct::findOrFail($id);
        $users = User::where('role', 'cashier')->get();
        $products = Product::all();
        $selectedFlavorId = $cashierProduct->flavor_id;
        $flavor = $cashierProduct->flavor;


        return view('admin.cashier-product.edit', compact('cashierProduct', 'users', 'products', 'flavor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CashierProductUpdateRequest $request, string $id)
    {
        try {
            // Temukan produk kasir berdasarkan ID
            $cashierProduct = CashierProduct::findOrFail($id);

            // Persiapkan data untuk diupdate
            $cashierProduct->fill([
                'stock' => $request->input('stock'),
            ]);

            // Simpan perubahan jika ada perubahan data
            if ($cashierProduct->isDirty()) {
                $cashierProduct->save();
                session()->flash('success', 'Berhasil melakukan perubahan pada produk kasir');
                return response()->json(['success' => true], 200);
            } else {
                session()->flash('info', 'Tidak melakukan perubahan pada produk kasir');
                return response()->json(['info' => true], 200);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terdapat kesalahan pada produk kasir: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $cashierProduct = CashierProduct::findOrFail($id);
            $cashierProduct->delete();

            return response(['status' => 'success', 'message' => 'Berhasil menghapus produk kasir']);
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan saat menghapus
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
