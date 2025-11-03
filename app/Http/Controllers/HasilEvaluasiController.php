<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class HasilEvaluasiController extends Controller 
{
    public function index()
    {
        $jenisPemilu = session('jenis_pemilu_terpilih') ?? session('jenis_pemilu', 'Presiden');

        if (!$jenisPemilu) {
            return redirect()->route('clustering.index')->with('error', 'Silahkan lakukan proses clustering terlebih dahulu.');
        }

        $data = DB::table('data_pemilih')
            ->whereNotNull('cluster')
            ->where('jenis_pemilu', $jenisPemilu)
            ->get();

        if ($data->isEmpty()) {
            return redirect()->route('clustering.index')->with('error', 'Data untuk hasil evaluasi tidak ditemukan. Silahkan lakukan proses clustering terlebih dahulu.');
        }

        $totalData = $data->count();
        $jumlahCluster = $data->pluck('cluster')->unique()->count();
        $jumlahPerCluster = $data->groupBy('cluster')->map->count();

        $clusterAvg = $data->groupBy('cluster')->map(function ($group) {
            return round($group->avg('partisipasi'), 2);
        });

        $clusterKategori = collect($clusterAvg)
            ->sortDesc()
            ->keys()
            ->mapWithKeys(function ($clusterId, $i) {
                $kategori = match($i) {
                    0 => 'Tinggi',
                    1 => 'cukup', 
                    2 => 'kurang',
                };
                return [$clusterId => $kategori];
            });

        $rankingKecamatan = $data->map(function ($item) {
            return [
                'kecamatan' => $item->kecamatan,
                'partisipasi' => round($item->partisipasi, 2),
                'cluster' => $item->cluster,
            ];
        })
        ->unique('kecamatan')
        ->values();

        $avgPerCluster = $data->groupBy('cluster')->map->avg('partisipasi')->sortDesc();
        $kategoriMap = $avgPerCluster->keys()->mapWithKeys(function ($cluster, $i) {
            return [$cluster => match($i) {
                0 => 'Tinggi',
                1 => 'Cukup',
                default => 'kurang'
            }];
        });

        $rankingKecamatan = $rankingKecamatan->transform(function ($item) use ($kategoriMap) {
        $item['kategori'] = $kategoriMap[$item['cluster']];
            return $item;
        })

        ->sortBy(function ($item) {
            return match($item['kategori']) {
            'Tinggi' => 0,
            'Cukup' => 1,
            'Rendah' => 2,
            default => 3
            };
        })
        ->values();

        $clusterTerbesar = $jumlahPerCluster->sortDesc()->keys()->first();
        $rataRataPartisipasi = round($data->avg('partisipasi'), 2);
        $labelsCluster = $jumlahPerCluster->keys()->map(fn($i) => "Cluster $i" );
        $jumlahPerClusterArray = array_values($jumlahPerCluster->toArray());

        $dataPartisipasi = $data->groupBy('kecamatan')->map(function ($item) {
            return round($item->avg('partisipasi'), 2);
        });

        $labelsKecamatan = $dataPartisipasi->keys();
        $valuesPartisipasi = $dataPartisipasi->values();

        $dataSuara = $data
            ->groupBy('kecamatan')
            ->map(function ($group) {
                return [
                    'total_sah' => $group->sum('suara_sah'),
                    'total_tidak_sah' => $group->sum('suara_tidak_sah'),
                ];
            });

        $labelsSuara = $dataSuara->keys();
        $suaraSah = $dataSuara->pluck('total_sah');
        $suaraTidakSah = $dataSuara->pluck('total_tidak_sah');

        $maxPart = $data->sortByDesc('partisipasi')->first();
        $minPart = $data->sortBy('partisipasi')->first();
        $evaluasiKesimpulan = "Kecamatan dengan partisipasi tertinggi adalah <strong>{$maxPart->kecamatan}</strong> sebesar <strong>" . round($maxPart->partisipasi, 2) . "%</strong>, dan terendah adalah <strong>{$minPart->kecamatan}</strong> sebesar <strong>" . round($minPart->partisipasi, 2) . "%</strong>.";

        $kesimpulan = "Berdasarkan hasil Clustering, Cluster {$clusterTerbesar} adalah yang paling dominan untuk jenis pemilu <strong>{$jenisPemilu}</strong>.";

        $daftarKecamatan = DB::table('data_pemilih')
            ->where('jenis_pemilu', $jenisPemilu)
            ->select('kecamatan')->distinct()->pluck('kecamatan');

        $wilayahRendah = $rankingKecamatan->where('kategori', 'Rendah')->pluck('kecamatan')->toArray();

        $intervensiKesimpulan = '';
        if (count($wilayahRendah) > 0) {
            $daftar = implode(', ', $wilayahRendah);
            $intervensiKesimpulan = "Wilayah-wilayah seperti <strong>{$daftar}</strong> termasuk dalam kategori partisipasi pemilih rendah berdasarkan hasil clustering. Disarankan untuk dilakukan intervensi atau perhatian lebih lanjut oleh pihak Bakesbangpol.";
        }

        return view('hasil-evaluasi', [
            'kesimpulan' => $kesimpulan,
            'totalData' => $totalData,
            'jumlahCluster' => $jumlahCluster,
            'clusterTerbesar' => $clusterTerbesar,
            'rataRataPartisipasi' => $rataRataPartisipasi,
            'jumlahPerCluster' => array_values($jumlahPerCluster->toArray()),
            'labels' => $jumlahPerCluster->keys()->map(fn($i) => "Cluster $i"),
            'labelsKecamatan' => $labelsKecamatan,
            'values' => $valuesPartisipasi,
            'suaraLabels' => $labelsSuara,
            'suaraSah' => $suaraSah,
            'suaraTidakSah' => $suaraTidakSah, 
            'evaluasiKesimpulan' => $evaluasiKesimpulan,
            'daftarKecamatan' => $daftarKecamatan,   
            'jenisPemilu' => $jenisPemilu,  
            'clusterKategori' => $clusterKategori,
            'avgPerCluster' => $avgPerCluster,  
            'rankingKecamatan' => $rankingKecamatan, 
            'intervensiKesimpulan' => $intervensiKesimpulan,
        ]);
    }

    public function preview(Request $request)
    {
        return $this->generatePdf($request, false);
    }


    public function exportPDF(Request $request)
    {
        return $this->generatePdf($request, true);
    }

    private function generatePdf(Request $request, $download = false)
    {
        $jenisPemilu = session('jenis_pemilu_terpilih') ?? session('jenis_pemilu', 'Presiden');
        $kecamatan = $request->input('kecamatan');

        $data = DB::table('data_pemilih')
            ->whereNotNull('cluster')
            ->where('jenis_pemilu', $jenisPemilu)
            ->when($kecamatan, fn($q) => $q->where('kecamatan', $kecamatan))
            ->get();

        
        if ($data->isEmpty()) {
            return back()->with('error', 'Data tidak tersedia untuk wilayah yang dipilih.');
        }

        $totalData = $data->count();
        $jumlahCluster = $data->pluck('cluster')->unique()->count();
        $jumlahPerCluster = $data->groupBy('cluster')->map->count();
        $clusterTerbesar = $jumlahPerCluster->sortDesc()->keys()->first();
        $rataRataPartisipasi = round($data->avg('partisipasi'), 2);
        $jumlahPerClusterArray = array_values($jumlahPerCluster->toArray());
        $labelsCluster = $jumlahPerCluster->keys()->map(fn($i) => "Cluster $i");

        $maxPart = $data->sortByDesc('partisipasi')->first();
        $minPart = $data->sortBy('partisipasi')->first();

        $evaluasiKesimpulan = "Wilayah dengan partisipasi tertinggi adalah <strong>{$maxPart->kecamatan}</strong> (" . round($maxPart->partisipasi, 2) . "%), dan terendah <strong>{$minPart->kecamatan}</strong> (" . round($minPart->partisipasi, 2) . "%).";
        $kesimpulan = $kecamatan
            ? "Cluster {$clusterTerbesar} paling dominan di {$kecamatan}."
            : "Cluster {$clusterTerbesar} paling dominan di seluruh wilayah.";


        $clusterDescriptions = [];
        $clusterKecamatanList = [];

        $clusterAvg = $data->groupBy('cluster')->map(function ($group) {
            return round($group->avg('partisipasi'), 2);
        });

        $clusterKategori = collect($clusterAvg)
            ->sortDesc()
            ->keys()
            ->mapWithKeys(function ($clusterId, $i) {
                $kategori = match($i) {
                    0 => 'Tinggi',
                    1 => 'Cukup',
                    default => 'Kurang',
                };
                return [$clusterId => $kategori];
            });

        $clusterDescriptions = [];

        foreach ($clusterAvg as $cluster => $avg) {
            $kategori = $clusterKategori[$cluster];
            $deskripsi = match($kategori) {
                'Tinggi' => "Cluster {$cluster} (Tinggi): Cluster {$cluster} merupakan wilayah dengan partisipasi pemilih tinggi. Wilayah ini menunjukkan tingkat antusiasme dan kesadaran politik yang sangat baik.",
                'Cukup' => "Cluster {$cluster} (Cukup): Cluster {$cluster} menunjukkan partisipasi pemilih cukup. Wilayh ini relatif aktif namun masih memiliki ruang untuk peningkatan.",
                'Kurang' => "Cluster {$cluster} (Kurang): Cluster {$cluster} memiliki partisipasi pemilih yang relatif rendah. Diperlukan perhatian dan intervensi lebih lanjut untuk meningkatkan partisipasi.",
            };

            $clusterDescriptions[] = $deskripsi;
        }

        $clusterKecamatanList = [];
        if (!$kecamatan) {
            $clusterKecamatanList = $data->groupBy('cluster')->map(function ($items, $cluster) {
                return [
                    'cluster' => $cluster,
                    'kecamatan' => $items->pluck('kecamatan')->unique()->values()->all()
                ];
            })->values()->all();
        } 


        $pdf = Pdf::loadView('hasil-evaluasi-pdf', [
            'totalData' => $totalData,
            'jumlahCluster' => $jumlahCluster,
            'clusterTerbesar' => $clusterTerbesar,
            'rataRataPartisipasi' => $rataRataPartisipasi,
            'jumlahPerCluster' => $jumlahPerClusterArray,
            'labels' => $labelsCluster,
            'evaluasiKesimpulan' => $evaluasiKesimpulan,
            'kesimpulan' => $kesimpulan,
            'kecamatan' => $kecamatan,
            'jenis_pemilu' => $jenisPemilu,
            'tanggal' => Carbon::now()->translatedFormat('d/m/y'),
            'clusterKecamatanList' => $clusterKecamatanList,
            'clusterDescriptions' => $clusterDescriptions,
        ])->setPaper('a4', 'portrait');

        if ($download) {
            $filename = $kecamatan ? "Evaluasi_{$kecamatan}.pdf" : 'hasil-evaluasi-bakesbangpol.pdf';
            return $pdf->download($filename);
        } else {
            return $pdf->stream();
        }
    }

    public function exportSemua()
    {
        $jenisPemilu = session('jenis_pemilu_terpilih') ?? session('jenis_pemilu', 'Presiden');

        $allClusterData = DB::table('data_pemilih')
            ->where('jenis_pemilu', $jenisPemilu)
            ->whereNotNull('cluster')
            ->get();

        if ($allClusterData->isEmpty()) {
            return back()->with('error', 'Data clustering belum tersedia.');
        }

        $clusterAvg = $allClusterData->groupBy('cluster')->map(function ($group) {
            return round($group->avg('partisipasi'), 2);
        });

        $clusterKategori = collect($clusterAvg)
            ->sortDesc()
            ->keys()
            ->mapWithKeys(function ($clusterId, $i) {
                $kategori = match($i) {
                    0 => 'Tinggi',
                    1 => 'Cukup',
                    default => 'Kurang',
                };
                return [$clusterId => $kategori];
            });

        $clusterDescriptionsMap = [];
        foreach ($clusterAvg as $cluster => $avg) {
            $kategori = $clusterKategori[$cluster];
            $deskripsi = match($kategori) {
                'Tinggi' => "Cluster {$cluster} (Tinggi): Cluster {$cluster} merupakan wilayah dengan partisipasi pemilih tinggi. Wilayah ini menunjukkan tingkat antusiasme dan kesadaran politik yang sangat baik.",
                'Cukup' => "Cluster {$cluster} (Cukup): Cluster {$cluster} menunjukkan tingkat partisipasi pemilih yang cukup. Wilayah ini relatif aktif namun masih memiliki ruang untuk peningkatan.",
                'Kurang' => "Cluster {$cluster} (Kurang): Cluster {$cluster} memiliki tingkat partisipasi pemilih yang rendah. Diperlukan perhatian dan intervensi lebih lanjut untuk meningkatkan partisipasi.",
            };

            $clusterDescriptionsMap[$cluster] = $deskripsi;
        }   
        
        $zip = new ZipArchive;
        $zipFileName = 'evaluasi_kecamatan_all.zip';
        $zipPath = storage_path("app/public/$zipFileName");
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $kecamatans = DB::table('data_pemilih')
            ->where('jenis_pemilu', $jenisPemilu)
            ->select('kecamatan')->distinct()->pluck('kecamatan');

        foreach ($kecamatans as $kecamatan) {
            $data = DB::table('data_pemilih')
                ->where('jenis_pemilu', $jenisPemilu)
                ->where('kecamatan', $kecamatan)
                ->get();

            if ($data->isEmpty()) continue;

            $clusterWilayah = optional($data->first())->cluster;

            $rataRata = round($data->avg('partisipasi'), 2);
            $max = $data->sortByDesc('partisipasi')->first();
            $min = $data->sortBy('partisipasi')->first();
            $jumlahPerCluster = $data->groupBy('cluster')->map->count();

            $kesimpulan = "Rata-rata partisipasi di kecamatan {$kecamatan} adalah {$rataRata}%.
            Tertinggi: ({$max->partisipasi}%),
            Terendah: ({$min->partisipasi}%).";

            $clusterDescriptions = [];
            if (isset($clusterDescriptionsMap[$clusterWilayah])) {
                $clusterDescriptions[] = $clusterDescriptionsMap[$clusterWilayah];
            }


            $pdf = Pdf::loadView('evaluasi-per-wilayah', [
                'kecamatan' => $kecamatan,
                'data' => $data,
                'rataPartisipasi' => $rataRata,
                'kesimpulan' => $kesimpulan,
                'jumlahPerCluster' => array_values($jumlahPerCluster->toArray()),
                'labels' => $jumlahPerCluster->keys(),
                'tanggal' => Carbon::now()->translatedFormat('d/m/y'),
                'jenis_pemilu' => $jenisPemilu,
                'clusterWilayah' => $clusterWilayah,
                'clusterDescriptions' => $clusterDescriptions
            ]);

            $pdfPath = storage_path("app/temp_{$kecamatan}.pdf");
            $pdf->save($pdfPath);
            $zip->addFile($pdfPath, "Evaluasi_{$kecamatan}.pdf");
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
    
}