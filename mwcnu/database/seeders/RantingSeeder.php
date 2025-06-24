<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ranting;

class RantingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelurahanList = [
            'karang tengah',
            'karang mulya',
            'karang timur',
            'pedurenan',
            'pondok bahar',
            'pondok pucung',
            'parung jaya',
        ];

        foreach ($kelurahanList as $kelurahan) {
            Ranting::create([
                'kelurahan' => $kelurahan,
            ]);
        }
    }
}
