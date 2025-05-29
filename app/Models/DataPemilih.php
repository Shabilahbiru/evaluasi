<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPemilih extends Model
{
    use HasFactory;

    protected $table = 'data_pemilih';

    protected $fillable = [
        'kecamatan',
        'kelurahan',
        'dpt_laki_laki',
        'dpt_perempuan',
        'dpt_total',
        'suara_sah',
        'suara_tidak_sah',
        'suara_total',
        'partisipasi',
    ];
}