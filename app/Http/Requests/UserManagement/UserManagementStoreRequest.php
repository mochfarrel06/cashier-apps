<?php

namespace App\Http\Requests\UserManagement;

use Illuminate\Foundation\Http\FormRequest;

class UserManagementStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'avatar' => ['required', 'image', 'max:1000', 'mimes:png,jpg,jpeg'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'transaction_code' => ['required', 'string'],
            'location' => ['required', 'string'],
            'role' => ['required', 'in:admin,cashier'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama pengguna tidak boleh kosong',
            'username.required' => 'Username tidak boleh kosong',
            'username.unique' => 'Username sudah di tambahkan',
            'email.required' => 'Email tidak boleh kosong',
            'email.unique' => 'Email sudah di tambahkan',
            'transaction_code.required' => 'Nomor transaksi tidak boleh kosong',
            'location.required' => 'Lokasi tidak boleh kosong',
            'role.required' => 'Role tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'password.confirmed' => 'Password dan konfirmasi password tidak sama',
            'avatar.required' => 'Gambar pengguna tidak boleh kosong',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.max' => 'Ukuran gambar tidak boleh lebih dari 1000 KB',
            'avatar.mimes' => 'Format gambar harus berupa PNG, JPG, atau JPEG'
        ];
    }
}
