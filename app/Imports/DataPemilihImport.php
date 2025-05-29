<?php
namespace App\Imports;

use App\Models\DataPemilih;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataPemilihImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new DataPemilih([
            'kecamatan' => $row['kecamatan'],
            'kelurahan' => $row['kelurahan'],
            'dpt_laki_laki' => $row['dpt_laki_laki'],
            'dpt_perempuan' => $row['dpt_perempuan'],
            'dpt_total' => $row['dpt_total'],
            'suara_sah' => $row['suara_sah'],
            'suara_tidak_sah' => $row['suara_tidak_sah'],
            'suara_total' => $row['suara_total'],
            'partisipasi' => $row['partisipasi'],
        ]);
    }
}