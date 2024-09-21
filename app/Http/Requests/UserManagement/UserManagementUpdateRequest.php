<?php

namespace App\Http\Requests\UserManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserManagementUpdateRequest extends FormRequest
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
        $userId = $this->route('user_management');
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($userId)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'transaction_code' => ['required', 'string'],
            'location' => ['required', 'string'],
            'role' => ['required', 'in:admin,cashier'],
            'password' => ['nullable', 'string', 'min:4', 'confirmed'],
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
            'password.min' => 'Password harus memiliki setidaknya 4 karakter',
            'password.confirmed' => 'Password dan konfirmasi password tidak sama'
        ];
    }
}
