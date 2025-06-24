<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anggota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user
        $user = User::create([
            'email' => 'admin@mwcnu.com',
            'password' => Hash::make('12341234'),
        ]);

        Anggota::create([
            'name' => 'Admin IT',
            'user_id' => $user->id,
            'role_id' => 1,
            'ranting_id' => 1,
            'phone' => '081234567890',
            'status' => 'active',
        ]);
    }
}
