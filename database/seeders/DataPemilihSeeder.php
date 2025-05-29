<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataPemilih;

class DataPemilihSeeder extends Seeder
{
    public function run(): void
    {
        DataPemilih::create([
            'kecamatan' => 'Andir',
            'kelurahan' => 'Ciroyom',
            'dpt_laki_laki' => 1500,
            'dpt_perempuan' => 1600,
            'dpt_total' => 3100,
            'suara_sah' => 2800,
            'suara_tidak_sah' => 200,
            'suara_total' => 3000,
            'partisipasi' => round((3500 / 4700) * 100, 2)
        ]);

        DataPemilih::create([
            'kecamatan' => 'Astana Anyar',
            'kelurahan' => 'Nyengseret',
            'dpt_laki_laki' => 1800,
            'dpt_perempuan' => 2000,
            'dpt_total' => 3800,
            'suara_sah' => 3100,
            'suara_tidak_sah' => 250,
            'suara_total' => 3350,
            'partisipasi' => round((3350 / 3800) * 100, 2)
        ]);
    }
}