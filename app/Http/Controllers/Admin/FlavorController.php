<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Flavor\FlavorStoreRequest;
use App\Models\Flavor;
use App\Models\Product;
use Illuminate\Http\Request;

class FlavorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flavors = Flavor::with('product')->get();

        return view('admin.flavor.index', compact('flavors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.flavor.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FlavorStoreRequest $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);

            $flavor = new Flavor([
                'product_id' => $product->id,
                'flavor_name' => $request->flavor_name
            ]);

            $flavor->save();

            session()->flash('success', 'Berhasil menambahkan varian produk');
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', 'Terdapat kesalahan pada proses varian produk: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
