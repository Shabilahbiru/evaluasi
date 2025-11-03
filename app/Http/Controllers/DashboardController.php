<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPemilih;
use App\Models\UserActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('jenis_pemilu')) {
            session(['jenis_pemilu' => $request->jenis_pemilu]);
        }

        $jenisPemilu = session('jenis_pemilu', DataPemilih::select('jenis_pemilu')->distinct()->pluck('jenis_pemilu')->first() ?? 'Presiden');
        $jenisPemiluList = DataPemilih::select('jenis_pemilu')->distinct()->pluck('jenis_pemilu');

        $query = DataPemilih::query()
            ->when($jenisPemilu, function ($q) use ($jenisPemilu) {
                return $q->where('jenis_pemilu', $jenisPemilu);
            });

        $summary = $query->select(
            'kecamatan',
            DB::raw('SUM(dpt_total) as total_pemilih'),
            DB::raw('SUM(dpt_laki_laki) as total_laki_laki'),
            DB::raw('SUM(dpt_perempuan) as total_perempuan'),
        )->groupBy('kecamatan')->get();

        $totalPemilih = $summary->sum('total_pemilih');
        $dptLakilaki = $summary->sum('total_laki_laki');
        $dptPerempuan = $summary->sum('total_perempuan');


        $jumlahKecamatan = $summary->count();

        $aktivitasTerakhir = UserActivity::latest()->take(3)->get();

        
        $partisipasiPerKecamatan = $query
            ->selectRaw('kecamatan, SUM(partisipasi) as total_partisipasi, SUM(suara_sah) as total_suara_sah, SUM(suara_tidak_sah) as total_suara_tidak_sah')
            ->groupBy('kecamatan')
            ->get();

        $scatterData = $query
            ->selectRaw('kecamatan, SUM(dpt_total) as total_pemilih, SUM(partisipasi) as total_partisipasi')
            ->groupBy('kecamatan')
            ->get();

        $fiturData = DataPemilih::where('jenis_pemilu', $jenisPemilu)
            ->whereNotNull('cluster')
            ->select('kecamatan', 'dpt_total', 'suara_total', 'partisipasi', 'cluster')
            ->get();

        
            return view('dashboard', compact(
            'totalPemilih', 
            'dptLakilaki',
            'dptPerempuan', 
            'jumlahKecamatan',
            'partisipasiPerKecamatan',
            'scatterData',
            'aktivitasTerakhir',
            'fiturData',
            'jenisPemilu',
            'jenisPemiluList',
            'summary'
        ));    
    }
}