<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aktivitas;

class AktivitasSeeder extends Seeder
{
    public function run(): void
    {
        Aktivitas::create(['kegiatan' => 'Data pemilih kecamatan Coblong diperbarui']);
        Aktivitas::create(['kegiatan' => 'Admin menambahkan 3 data partisipasi baru']);
        Aktivitas::create(['kegiatan' => 'Grafik partisipasi berhasil diperbaharui']);
    }
}
