<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'jabatan' => 'Admin'
        ]);

        Role::create([
            'jabatan' => 'Tanfidiyah'
        ]);

        Role::create([
            'jabatan' => 'Tanfidiyah ranting'
        ]);

        Role::create([
            'jabatan' => 'Sekretaris'
        ]);
    }
}
