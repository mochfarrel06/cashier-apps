<?php

namespace App\Http\Controllers\Admin\Flavor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Flavor\FlavorStoreRequest;
use App\Models\Flavor;
use App\Models\Product;

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
        $flavor = Flavor::findOrFail($id);
        $product = $flavor->product;

        return view('admin.flavor.show', compact('flavor', 'product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $flavor = Flavor::findOrFail($id);
        $products = Product::all();

        return view('admin.flavor.edit', compact('flavor', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FlavorStoreRequest $request, string $id)
    {
        try {
            $flavor = Flavor::findOrFail($id);
            $product = Product::findOrFail($request->product_id);

            $flavors = $request->all();
            $flavors['product_id'] = $product->id;

            $flavor->fill($flavors);

            if ($flavor->isDirty()) {
                $flavor->save();

                session()->flash('success', 'Berhasil melakukan perubahan pada varian produk');
                return response()->json(['success' => true], 200);
            } else {
                session()->flash('info', 'Tidak melakukan perubahan pada varian produk');
                return response()->json(['info' => true], 200);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terdapat kesalahan pada varian produk: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $flavor = Flavor::findOrFail($id);
            $flavor->delete();

            return response(['status' => 'success', 'message' => 'Berhasil menghapus varian produk']);
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan saat menghapus
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
