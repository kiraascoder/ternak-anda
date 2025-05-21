<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Admin Utama',
            'email' => 'adminpakengternak01@gmail.com',
            'password' => Hash::make('adminpakengternak'),
            'phone' => '08123456789',
            'role' => 'Admin',
        ]);
    }
}
