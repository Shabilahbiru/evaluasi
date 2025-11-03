<?php
namespace App\Imports;

use App\Models\DataPemilih;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataPemilihImport implements ToModel, WithHeadingRow
{
    protected $jenisPemilu;

    public function __construct($jenisPemilu)
    {
        $this->jenisPemilu = $jenisPemilu;
    }

    public function model(array $row)
    {
        return new DataPemilih([
            'kecamatan' => $row['kecamatan'],
            'dpt_laki_laki' => $row['dpt_laki_laki'],
            'dpt_perempuan' => $row['dpt_perempuan'],
            'dpt_total' => $row['dpt_total'],
            'suara_sah' => $row['suara_sah'],
            'suara_tidak_sah' => $row['suara_tidak_sah'],
            'suara_total' => $row['suara_total'],
            'partisipasi' => $this->hitungPartisipasi($row['suara_total'], $row['dpt_total']), 
            'jenis_pemilu' => $this->jenisPemilu,
        ]);
    }

    private function hitungPartisipasi($suaraTotal, $dptTotal)
    {
        return $dptTotal > 0 ? round(($suaraTotal / $dptTotal) * 100, 2) : 0;
    }
}