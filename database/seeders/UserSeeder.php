<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat akun Admin
        User::create([
            'name' => 'Suster Kepala Panti',
            'login' => '081315672350',
            'email' => 'admin@vitadulcedo.id',
            'password' => Hash::make('admin123'),
            'pin' => '12345',
            'role' => 'admin',
            'position' => 'Ketua Panti',
            'address' => 'Jl. Flamboyan Blok KM 10-11, Kota Harapan Indah, Bekasi',
        ]);

        // 2. Buat beberapa akun User biasa (Donatur)
        User::create([
            'name' => 'Budi Santoso',
            'login' => '081234567890',
            'email' => 'budi.santoso@example.com',
            'password' => Hash::make('donatur123'),
            'pin' => '84920',
            'role' => 'user',
            'organization' => 'PT Teknologi Harapan',
            'address' => 'Jakarta Selatan',
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'login' => '085712345678',
            'email' => 'siti.aminah@example.com',
            'password' => Hash::make('password123'),
            'pin' => '51382',
            'role' => 'user',
            'organization' => 'Komunitas Berbagi Kasih',
        ]);
        
        User::create([
            'name' => 'Ahmad Fadhil',
            'login' => '089698765432',
            'email' => null, // Email dikosongkan untuk tes opsional
            'password' => Hash::make('password123'),
            'pin' => '99812',
            'role' => 'user',
        ]);
    }
}
