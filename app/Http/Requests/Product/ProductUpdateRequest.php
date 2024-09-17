<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'code' => ['required', 'string', 'unique:products,code,' . $productId],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price_retail' => ['required', 'numeric'],
            'price_pack' => ['required', 'numeric'],
            'items_per_pack' => ['required', 'numeric'],
            'photo' => ['nullable', 'image', 'max:1000', 'mimes:png,jpg,jpeg']
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Kode produk tidak boleh kosong',
            'code.unique' => 'Kode produk sudah di tambahkan',
            'name.required' => 'Nama produk tidak boleh kosong',
            'description.required' => 'Deskripsi produk tidak boleh kosong',
            'price_retail.required' => 'Harga Produk Eceran tidak boleh kosong',
            'price_pack.required' => 'Harga Produk Per Pack tidak boleh kosong',
            'items_per_pack.required' => 'Jumlah Produk Per Pack tidak boleh kosong',
            'photo.required' => 'Gambar produk tidak boleh kosong',
            'photo.image' => 'File harus berupa gambar',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 1000 KB',
            'photo.mimes' => 'Format gambar harus berupa PNG, JPG, atau JPEG'
        ];
    }
}
