<?php

namespace App\Http\Requests\CashierProduct;

use Illuminate\Foundation\Http\FormRequest;

class CashierProductUpdateRequest extends FormRequest
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
            'user_id' => ['nullable', 'exists:users,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'flavor_id' => ['nullable', 'exists:flavors,id'],
            'stock' => ['required', 'numeric', 'min:1']
        ];
    }

    public function messages()
    {
        return [
            'stock.required' => 'Stok produk tidak boleh kosong',
            'stock.min' => 'Stok produk tidak boleh kurang dari 1'
        ];
    }
}
