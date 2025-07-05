<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenyuluhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Penyuluh 01',
            'email' => 'penyuluhpakengternak01@gmail.com',
            'password' => Hash::make('penyuluhpakengternak'),
            'phone' => '08123456789',
            'role' => 'Penyuluh',
        ]);
    }
}
