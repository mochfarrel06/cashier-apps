<?php

namespace App\Http\Requests\Flavor;

use Illuminate\Foundation\Http\FormRequest;

class FlavorStoreRequest extends FormRequest
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
            'product_id' => ['required', 'numeric', 'exists:products,id'],
            'flavor_name' => ['required', 'string'],
            'price_retail' => ['required', 'numeric'],
            'price_pack' => ['required', 'numeric'],

        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Data produk tidak boleh kosong',
            'flavor_name.required' => 'Varian produk tidak boleh kosong',
            'price_retail.required' => 'Harga Produk Eceran tidak boleh kosong',
            'price_pack.required' => 'Harga Produk Per Pack tidak boleh kosong',

        ];
    }
}
