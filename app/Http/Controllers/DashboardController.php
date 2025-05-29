<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPemilih;
use App\Models\Aktivitas;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPemilih = DataPemilih::sum('dpt_total');
        $dptLakilaki = DataPemilih::sum('dpt_laki_laki');
        $dptPerempuan = DataPemilih::sum('dpt_perempuan');
        $jumlahKecamatan = DataPemilih::select('kecamatan')->distinct()->count();
        $aktivitasTerakhir = Aktivitas::latest()->take(5)->get();

        $partisipasiPerKecamatan = DataPemilih::selectraw('kecamatan, SUM(partisipasi) as total_partisipasi')
            ->groupBy('kecamatan')
            ->pluck('total_partisipasi', 'kecamatan');

        $scatterData = DataPemilih::selectRaw('kecamatan, SUM(dpt_total) as total_pemilih, SUM(partisipasi) as total_partisipasi')
            ->groupBy('kecamatan')
            ->get();
        
            return view('dashboard', compact(
            'totalPemilih', 
            'dptLakilaki',
            'dptPerempuan', 
            'jumlahKecamatan',
            'partisipasiPerKecamatan',
            'scatterData',
            'aktivitasTerakhir'
        ));    
    }
}