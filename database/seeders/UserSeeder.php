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
                'name' => 'admin 1',
                'avatar' => '/uploads/profile/admin-avatar.png',
                'username' => 'admin1',
                'email' => 'admin@gmail.com',
                'location' => 'Kediri',
                'role' => 'admin',
                'password' => static::$password ??= Hash::make('password'),
            ],
            [
                'name' => 'kasir 1',
                'avatar' => '/uploads/profile/admin-avatar.png',
                'username' => 'kasir1',
                'email' => 'kasir@gmail.com',
                'location' => 'Jl. Majapahit No 12 Kediri',
                'role' => 'cashier',
                'password' => static::$password ??= Hash::make('password'),
            ],
            [
                'name' => 'kasir 2',
                'avatar' => '/uploads/profile/admin-avatar.png',
                'username' => 'kasir2',
                'email' => 'kasir2@gmail.com',
                'location' => 'Jl. Brawijaya No 22 Kediri',
                'role' => 'cashier',
                'password' => static::$password ??= Hash::make('password'),
            ],
        ]);
    }
}
