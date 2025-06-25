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
            'id' => '1',
            'jabatan' => 'Admin'
        ]);

        Role::create([
            'id' => '2',
            'jabatan' => 'Tanfidiyah'
        ]);

        Role::create([
            'id' => '3',
            'jabatan' => 'Tanfidiyah ranting'
        ]);

        Role::create([
            'id' => '4',
            'jabatan' => 'Sekretaris'
        ]);
    }
}
