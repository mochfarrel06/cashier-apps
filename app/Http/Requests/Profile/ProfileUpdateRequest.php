<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
        $userId = Auth::id(); // Mengambil ID user yang sedang login
        return [
            'avatar' => ['nullable', 'image', 'max:1000', 'mimes:png,jpg,jpeg'],
            'name' => ['required', 'max:50'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($userId)
            ],
            'role' => ['nullable'],
            'transaction_code' => ['required', 'string'],
            'location' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'username.required' => 'Username tidak boleh kosong',
            'username.unique' => 'Username sudah di tambahkan',
            'transaction_code.required' => 'Nomor transaksi tidak boleh kosong',
            'location.required' => 'Email tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.unique' => 'Email sudah di tambahkan',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.max' => 'Ukuran gambar tidak boleh lebih dari 1000 KB',
            'avatar.mimes' => 'Format gambar harus berupa PNG, JPG, atau JPEG'
        ];
    }
}
