<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartStoreRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1', // Menambah validasi quantity minimal 1
            'cashier_product_id' => 'required|exists:cashier_products,id',
            'purchase_type' => 'required|in:retail,pack',
        ];
    }

    public function messages()
    {
        return [
            'quantity.required' => 'Jumlah produk tidak boleh kosong',
            'quantity.min:1' => 'Jumlah produk tidak boleh kurang dari 1',
            'cashier_product_id..required' => 'Data kasir produk tidak boleh kosong',
            'purchase_type..required' => 'Type pembelian tidak boleh kosong',
        ];
    }
}
