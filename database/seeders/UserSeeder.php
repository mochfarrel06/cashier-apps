<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    protected static ?string $password;

    public function run(): void
    {
        User::insert([
            [
                'name' => 'admin',
                'avatar' => '/uploads/profile/admin-avatar.png',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'location' => 'Riau',
                'transaction_code' => 'ADMIN001',
                'role' => 'admin',
                'password' => static::$password ??= Hash::make('password'),
            ],
            [
                'name' => 'Gerobak 1',
                'avatar' => '/uploads/profile/kasir1.png',
                'username' => 'gerobak1',
                'email' => 'gerobak1@gmail.com',
                'location' => 'Jl. Majapahit No 12 Kediri',
                'transaction_code' => 'KSR001',
                'role' => 'cashier',
                'password' => static::$password ??= Hash::make('password'),
            ],
            [
                'name' => 'Gerobak 2',
                'avatar' => '/uploads/profile/kasir2.png',
                'username' => 'gerobak2',
                'email' => 'kasir2@gmail.com',
                'location' => 'Jl. Brawijaya No 22 Kediri',
                'transaction_code' => 'KSR002',
                'role' => 'cashier',
                'password' => static::$password ??= Hash::make('password'),
            ],
        ]);
    }
}
