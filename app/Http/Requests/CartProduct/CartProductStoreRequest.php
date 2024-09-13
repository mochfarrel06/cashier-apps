<?php

namespace App\Http\Requests\CartProduct;

use Illuminate\Foundation\Http\FormRequest;

class CartProductStoreRequest extends FormRequest
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
        return [
            'cart_id' => ['required', 'numeric', 'exists:carts,id'],
            'product_id' => ['required', 'numeric', 'exists:products,id'],
            'flavor_id' => ['required', 'numeric', 'exists:flavors,id'],
            'stock' => ['required', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            'cart_id.required' => 'Data kasir tidak boleh kosong',
            'product_id.required' => 'Data produk tidak boleh kosong',
            'flavor_id.required' => 'Data varian produk tidak boleh kosong',
            'stock.required' => 'Stok tidak boleh kosong',
        ];
    }
}
