<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartUpdateRequest extends FormRequest
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
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'name' => ['required', 'string'],
            'location' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Data kasir tidak boleh kosong',
            'name.required' => 'Nama kasir tidak boleh kosong',
            'location.required' => 'Lokasi tidak boleh kosong',
        ];
    }
}
