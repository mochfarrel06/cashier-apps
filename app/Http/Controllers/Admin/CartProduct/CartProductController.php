<?php

namespace App\Http\Controllers\Admin\CartProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartProduct\CartProductStoreRequest;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Flavor;
use App\Models\Product;
use Illuminate\Http\Request;

class CartProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartProducts = CartProduct::with(['cart', 'product', 'flavor'])->get();

        return view('admin.cart-product.index', compact('cartProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $carts = Cart::all();
        $products = Product::all();
        $flavors = Flavor::all();

        return view('admin.cart-product.create', compact('carts', 'products', 'flavors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartProductStoreRequest $request)
    {
        try {
            $cart = Cart::findOrFail($request->cart_id);
            $product = Product::findOrFail($request->product_id);
            $flavor = Flavor::findOrFail($request->flavor_id);

            $cartProduct = new CartProduct([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'flavor_id' => $flavor->id,
                'stock' => $request->stock
            ]);

            $cartProduct->save();

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
        $cartProduct = CartProduct::findOrFail($id);
        $cart = $cartProduct->cart;
        $product = $cartProduct->product;
        $flavor = $cartProduct->flavor;

        return view('admin.cart-product.show', compact('cartProduct', 'cart', 'product', 'flavor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cartProduct = CartProduct::findOrFail($id);
        $carts = Cart::all();
        $products = Product::all();
        $flavors = Flavor::all();

        return view('admin.cart-product.edit', compact('cartProduct', 'carts', 'products', 'flavors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $cartProduct = CartProduct::findOrFail($id);
            $cart = Cart::findOrFail($request->cart_id);
            $product = Product::findOrFail($request->product_id);
            $flavor = Flavor::findOrFail($request->flavor_id);

            $cartProducts = $request->all();
            $cartProducts['cart_id'] = $cart->id;
            $cartProducts['product_id'] = $product->id;
            $cartProducts['flavor_id'] = $flavor->id;

            $cartProduct->fill($cartProducts);

            if ($cartProduct->isDirty()) {
                $cartProduct->save();

                session()->flash('success', 'Berhasil melakukan perubahan pada kasir produk');
                return response()->json(['success' => true], 200);
            } else {
                session()->flash('info', 'Tidak melakukan perubahan pada kasir produk');
                return response()->json(['info' => true], 200);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terdapat kesalahan pada kasir produk: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $cartProduct = CartProduct::findOrFail($id);
            $cartProduct->delete();

            return response(['status' => 'success', 'message' => 'Berhasil menghapus kasir produk']);
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan saat menghapus
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
