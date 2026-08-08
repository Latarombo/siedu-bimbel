<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Ketentuan Penggunaan #3: akun Admin dibuat lewat seeder,
     * tidak ada form registrasi publik untuk role ini.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@siedu.test'],
            [
                'role' => 'admin',
                'name' => 'Admin Siedu',
                'password' => Hash::make('password'), // GANTI setelah seed pertama kali
            ]
        );
    }
}