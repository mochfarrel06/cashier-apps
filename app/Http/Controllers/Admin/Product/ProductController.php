<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Product;
use App\Traits\FileUploadTrait;

class ProductController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request)
    {
        try {
            $imagePath = $this->uploadImage($request, 'photo');

            $product = new Product([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'items_per_pack' => $request->items_per_pack,
                'photo' => isset($imagePath) ? $imagePath : 'photo'
            ]);

            $product->save();

            session()->flash('success', 'Berhasil menambahkan data produk');
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', 'Terdapat kesalahan pada proses data produk: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);

        return view('admin.product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);

            $products = $request->except('photo');

            if ($request->hasFile('photo')) {
                if ($product->photo && file_exists(public_path($product->photo))) {
                    unlink(public_path($product->photo));
                }

                $imagePath = $this->uploadImage($request, 'photo');
                $products['photo'] = $imagePath;
            }

            $product->fill($products);

            if ($product->isDirty()) {
                $product->save();

                session()->flash('success', 'Berhasil melakukan perubahan pada data produk');
                return response()->json(['success' => true], 200);
            } else {
                session()->flash('info', 'Tidak melakukan perubahan pada data produk');
                return response()->json(['info' => true], 200);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terdapat kesalahan pada data produk: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->photo) {
                $photoPath = public_path($product->photo);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }

            $product->delete();

            return response(['status' => 'success', 'message' => 'Berhasil menghapus data produk']);
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan saat menghapus
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
