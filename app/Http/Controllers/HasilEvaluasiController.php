<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

use function PHPUnit\Framework\returnSelf;

class HasilEvaluasiController extends Controller 
{
    public function index()
    {
        $data = DB::table('data_pemilih')->whereNotNull('cluster')->get();

        $totalData = $data->count();
        $jumlahCluster = $data->pluck('cluster')->unique()->count();
        $jumlahPerCluster = $data->groupBy('cluster')->map->count();

        $clusterTerbesar = $jumlahPerCluster->sortDesc()->keys()->first();
        $rataRataPartisipasi = round($data->avg('partisipasi'), 2);
        $labelsCluster = $jumlahPerCluster->keys()->map(fn($i) => "Cluster $i" );
        $jumlahPerClusterArray = array_values($jumlahPerCluster->toArray());

        $dataPartisipasi = DB::table('data_pemilih')
            ->select('kecamatan', DB::raw('AVG(partisipasi) as rata_partisipasi'))
            ->groupBy('kecamatan')
            ->get();

        $labelsKecamatan = $dataPartisipasi->pluck('kecamatan');
        $valuesPartisipasi = $dataPartisipasi->pluck('rata_partisipasi');

        $maxPart = $dataPartisipasi->sortByDesc('rata_partisipasi')->first();
        $minPart = $dataPartisipasi->sortBy('rata_partisipasi')->first();
        $evaluasiKesimpulan = "Kecamatan dengan partisipasi tertinggi adalah <strong>{$maxPart->kecamatan}</strong> sebesar <strong>".round($maxPart->rata_partisipasi, 2)."%</strong>, dan terendah adalah <strong>{$minPart->kecamatan}</strong> sebesar <strong>".round($minPart->rata_partisipasi, 2)."%</strong>.";

        $dataSuara = DB::table('data_pemilih')
            ->select('kecamatan',
            DB::raw('SUM(suara_sah) as total_sah'),
            DB::raw('SUM(suara_tidak_sah) as total_tidak_sah'))
            ->groupBy('kecamatan')
            ->get();

        $labelsSuara = $dataSuara->pluck('kecamatan');
        $suaraSah = $dataSuara->pluck('total_sah');
        $suaraTidakSah = $dataSuara->pluck('total_tidak_sah');

        $kesimpulan = "Berdasarkan hasil Clustering, Cluster {$clusterTerbesar} adalah yang paling dominan. Wilayah dalam cluster ini menunjukkan karakteristik pemilih yang mirip dan relatif tinngi.";

        $daftarKecamatan = DB::table('data_pemilih')
            ->select('kecamatan')
            ->distinct()
            ->pluck('kecamatan');

        return view('hasil-evaluasi', [
            'kesimpulan' => $kesimpulan,
            'totalData' => $totalData,
            'jumlahCluster' => $jumlahCluster,
            'clusterTerbesar' => $clusterTerbesar,
            'rataRataPartisipasi' => $rataRataPartisipasi,
            'jumlahPerCluster' => $jumlahPerClusterArray,
            'labels' => $labelsCluster,
            'labelsKecamatan' => $labelsKecamatan,
            'values' => $valuesPartisipasi,
            'suaraLabels' => $labelsSuara,
            'suaraSah' => $suaraSah,
            'suaraTidakSah' => $suaraTidakSah, 
            'evaluasiKesimpulan' => $evaluasiKesimpulan,
            'daftarKecamatan' => $daftarKecamatan,          
        ]);
    }

    public function preview(Request $request)
{
    $kecamatan = $request->input('kecamatan');

    $data = DB::table('data_pemilih')
        ->when($kecamatan, fn($q) => $q->where('kecamatan', $kecamatan))
        ->get();

    if ($data->count() === 0) {
        return back()->with('error', 'Tidak ada data untuk kecamatan ini.');
    }

    $totalData = $data->count();
    $jumlahCluster = $data->pluck('cluster')->unique()->count();
    $jumlahPerCluster = $data->groupBy('cluster')->map->count();
    $clusterTerbesar = $jumlahPerCluster->sortDesc()->keys()->first();
    $rataRataPartisipasi = round($data->avg('partisipasi'), 2);
    $jumlahPerClusterArray = array_values($jumlahPerCluster->toArray());

    $maxPart = $data->sortByDesc('partisipasi')->first();
    $minPart = $data->sortBy('partisipasi')->first();

    $evaluasiKesimpulan = "Wilayah dengan partisipasi tertinggi adalah <strong>{$maxPart->kecamatan}, {$maxPart->kelurahan}</strong> ({$maxPart->partisipasi}%), dan terendah <strong>{$minPart->kecamatan}, {$minPart->kelurahan}</strong> ({$minPart->partisipasi}%).";
    $kesimpulan = "Cluster {$clusterTerbesar} paling dominan di {$kecamatan}.";

    $pdf = Pdf::loadView('hasil-evaluasi-pdf', [
        'totalData' => $totalData,
        'jumlahCluster' => $jumlahCluster,
        'clusterTerbesar' => $clusterTerbesar,
        'rataRataPartisipasi' => $rataRataPartisipasi,
        'jumlahPerCluster' => $jumlahPerClusterArray,
        'labels' => $jumlahPerCluster->keys()->map(fn($i) => "Cluster $i"),
        'evaluasiKesimpulan' => $evaluasiKesimpulan,
        'kesimpulan' => $kesimpulan,
        'kecamatan' => $kecamatan
    ]);

    return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="PreviewEvaluasi_'.$kecamatan.'.pdf"');
}


    public function exportPDF(Request $request)
    {
        $kecamatan = $request->input('kecamatan');
        $data = DB::table('data_pemilih')
            ->when($kecamatan, function ($query) use ($kecamatan) {
                return $query->where('kecamatan', $kecamatan);
            }) 
            ->get();

        if ($data->count() === 0) {
            return back()->with('error', 'Data tidak ditemukan untuk kecamatan yang dipilih.');
        }

        $totalData = $data->count();
        $jumlahCluster = $data->pluck('cluster')->unique()->count();
        $jumlahPerCluster = $data->groupBy('cluster')->map->count();
        $clusterTerbesar = $jumlahPerCluster->sortDesc()->keys()->first();
        $rataRataPartisipasi = round($data->avg('partisipasi'), 2);
        $labelsCluster = $jumlahPerCluster->keys()->map(fn($i) => "Cluster $i");
        $jumlahPerClusterArray = array_values($jumlahPerCluster->toArray());


        $maxPart = $data->sortByDesc('partisipasi')->first();
        $minPart = $data->sortBy('partisipasi')->first();

        $kesimpulan = $kecamatan
            ? "Cluster {$clusterTerbesar} paling dominan di {$kecamatan}."
            : "Cluster {$clusterTerbesar} paling dominan di seluruh wilayah.";

        $evaluasiKesimpulan = $maxPart && $minPart 
            ? "Wilayah dengan partisipasi tertinggi adalah <strong>{$maxPart->kecamatan}, {$maxPart->kelurahan}</strong> ({$maxPart->partisipasi}%), dan terendah <strong>{$minPart->kecamatan}, {$minPart->kelurahan}</strong> ({$minPart->partisipasi}%)."
            : "Data tidak cukup untuk evaluasi.";

        $pdf = Pdf::loadView('hasil-evaluasi-pdf', [
            'totalData' => $totalData,
            'jumlahCluster' => $jumlahCluster,
            'clusterTerbesar' => $clusterTerbesar,
            'rataRataPartisipasi' => $rataRataPartisipasi,
            'jumlahPerCluster' => $jumlahPerClusterArray,
            'labels' => $labelsCluster,
            'values' => $data->pluck('partisipasi'),
            'evaluasiKesimpulan' => $evaluasiKesimpulan,
            'kesimpulan' => $kesimpulan,
            'kecamatan' => $kecamatan,
            'tanggal' => Carbon::now()->translatedFormat('d/m/y')

        ])->setPaper('a4', 'potrait');

        $namaFile = $kecamatan ? "Evaluasi_{$kecamatan}.pdf" : 'hasil-evaluasi-bakesbangpol.pdf';

        return $pdf->download($namaFile);

    }

    public function exportSemua()
    {
        $kecamatans = DB:: table('data_pemilih')->select('kecamatan')->distinct()->pluck('kecamatan');
        $zip = new ZipArchive;
        $zipFileName = 'evaluasi_kecamatan_all.zip';

        $zipPath = storage_path("app/public/$zipFileName");
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($kecamatans as $kecamatan) {
            $data = DB::table('data_pemilih')->where('kecamatan', $kecamatan)->get();

            if ($data->count() == 0) continue;

            $total = $data->count();
            $rataRata = round($data->avg('partisipasi'), 2);
            $max = $data->sortByDesc('partisipasi')->first();
            $min = $data->sortBy('partisipasi')->first();
            $jumlahPerCluster = $data->groupBy('cluster')->map->count();

            $kesimpulan = "Rata-rata partisipasi di kecamatan {$kecamatan} adalah {$rataRata}%.
            Tertinggi: {$max->kelurahan} ({$max->partisipasi}%),
            Terendah: {$min->kelurahan} ({$min->partisipasi}%).";

            $pdf = Pdf::loadView('evaluasi-per-wilayah', [
                'kecamatan' => $kecamatan,
                'data' => $data,
                'rataPartisipasi' => $rataRata,
                'kesimpulan' => $kesimpulan,
                'jumlahPerCluster' => array_values($jumlahPerCluster->toArray()),
                'labels' => $jumlahPerCluster->keys(),
                'tanggal' => Carbon::now()->translatedFormat('d/m/y')
            ]);

            $pdfPath = storage_path("app/temp_{$kecamatan}.pdf");
            $pdf->save($pdfPath);
            $zip->addFile($pdfPath, "Evaluasi_{$kecamatan}.pdf");
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
    
}