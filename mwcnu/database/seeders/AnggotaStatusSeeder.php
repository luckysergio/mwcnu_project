<?php

namespace Database\Seeders;

use App\Models\AnggotaStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnggotaStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusList = [
            'MWC',
            'Ranting',
        ];

        foreach ($statusList as $status) {
            AnggotaStatus::create([
                'status' => $status,
            ]);
        }
    }
}
