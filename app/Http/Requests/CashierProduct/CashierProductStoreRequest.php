<?php

namespace App\Http\Requests\CashierProduct;

use Illuminate\Foundation\Http\FormRequest;

class CashierProductStoreRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'flavor_id' => ['required', 'exists:flavors,id'],
            'stock' => ['required', 'numeric', 'min:1']
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Data kasir tidak boleh kosong',
            'product_id.required' => 'Data produk tidak boleh kosong',
            'flavor_id.required' => 'Varian produk tidak boleh kosong',
            'stock.required' => 'Jumlah produk tidak boleh kosong',
            'stock.min' => 'Jumlah produk tidak boleh kurang dari 1'
        ];
    }
}
