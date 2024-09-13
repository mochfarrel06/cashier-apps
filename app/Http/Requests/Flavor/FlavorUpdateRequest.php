<?php

namespace App\Http\Requests\Flavor;

use Illuminate\Foundation\Http\FormRequest;

class FlavorUpdateRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Data produk tidak boleh kosong',
            'flavor_name.required' => 'Varian produk tidak boleh kosong',
        ];
    }
}
