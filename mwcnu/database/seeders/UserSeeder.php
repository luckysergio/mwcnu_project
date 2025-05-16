<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'AdminMWCNU',
            'email' => 'admin@mwcnu.com',
            'password' => '12341234',
            'status' => 'approved',
            'role_id' => '1',
        ]);
        User::create([
            'name' => 'ketuaMWCNU',
            'email' => 'ketua@mwcnu.com',
            'password' => '12341234',
            'status' => 'approved',
            'role_id' => '1',
        ]);
    }
}
