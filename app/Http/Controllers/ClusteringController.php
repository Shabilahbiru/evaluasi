<?php
namespace App\Http\Controllers;

use App\Models\DataPemilih;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ClusteringController extends Controller
{
    public function index(Request $request)
    {
        $jenisPemilu = $request->input('jenis_pemilu') ?? session('jenis_pemilu', 'Presiden');
        
        $data =DB::table('data_pemilih')
            ->whereNotNull('cluster')
            ->when($jenisPemilu, function ($query) use ($jenisPemilu) {
                $query->where('jenis_pemilu', $jenisPemilu);
            })
            ->orderBy('cluster')
            ->orderBy('kecamatan')
            ->get();

        $totalData = $data->count();
        $jumlahCluster = $data->pluck('cluster')->unique()->count();
        $jumlahPerCluster = $data->groupBy('cluster')->map(function($group) {
            return $group->count();
        });

        $clusterTerbesar = $jumlahPerCluster->sortDesc()->keys()->first();

        $kesimpulan = "Berdasarkan hasil clustering, Cluster {$clusterTerbesar} merupakan kelompok dengan jumlah data terbanyak. 
        Hal ini menunjukkan bahwa sebagian besar wilayah memiliki karakteristik yang serupa dalam hal partisipasi pemilih.";

        session(['jenis_pemilu' => $jenisPemilu]);

        // Hitung rata-rata partisipasi tiap cluster
        $clusterAvg = $data->groupBy('cluster')->map(function ($group) {
            return round($group->avg('partisipasi'), 2);
        });

        // Urutkan dan tetapkan kategori otomatis: Tinggi, Cukup, Rendah
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

        // Bangun deskripsi final
        $deskripsiPerCluster = [];
        foreach ($clusterAvg as $cluster => $avg) {
            $kategori = $clusterKategori[$cluster];
            $deskripsi = match($kategori) {
                'Tinggi' => "Cluster {$cluster} (Tinggi): Cluster {$cluster} merupakan wilayah dengan partisipasi pemilih tinggi.",
                'Cukup' => "Cluster {$cluster} (Cukup): Cluster {$cluster} menunjukkan partisipasi pemilih cukup.",
                'Kurang' => "Cluster {$cluster} (Kurang): Cluster {$cluster} memiliki partisipasi pemilih yang relatif rendah.",
            };

        $deskripsiPerCluster[$cluster] = [
            'kategori' => $kategori,
            'rata_partisipasi' => $avg,
            'deskripsi' => $deskripsi,
        ];
    }

        return view('clustering', [
            'data' => $data,
            'totalData' => $totalData,
            'jumlahCluster' => $jumlahCluster,
            'clusterTerbesar' => $clusterTerbesar,
            'jumlahPerCluster' => $jumlahPerCluster->values(), // e.g. [10, 15, 5]
            'labels' => $jumlahPerCluster->keys()->map(fn($i) => "Cluster $i"),
            'kesimpulan' => $kesimpulan,
            'fiturData' => $data->map(function($item) {
                return [
                    'kecamatan' => $item->kecamatan,
                    'dpt_total' => (float) $item->dpt_total,
                    'suara_total' => (float) $item->suara_total,
                    'partisipasi' => (float) $item->partisipasi,
                    'cluster' => $item->cluster
                ];
            }),
            'jenisPemilu' => $jenisPemilu,
            'deskripsiPerCluster' => $deskripsiPerCluster,
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'jenis_pemilu' => 'required',
        ]);

        DB::table('data_pemilih')
            ->where('jenis_pemilu', $request->jenis_pemilu)
            ->update(['cluster' => null]);

        $data = DB::table('data_pemilih')
            ->where('jenis_pemilu', $request->jenis_pemilu)   
            ->select('id', 'dpt_total', 'suara_total', 'partisipasi')
            ->get();

        $points = $data->map(function($item) {
            return [
                'id' => $item->id,
                'features' => [(float)$item->dpt_total, (float)$item->suara_total, (float)$item->partisipasi],   
            ];
        });

        $k = 3;

        if ($points->isEmpty()) {
        return redirect()->back()->with('error', 'Data pemilih untuk jenis pemilu ' . $request->jenis_pemilu . ' tidak ditemukan.');
        }

        if ($points->count() < $k) {
        return redirect()->back()->with('error', 'Jumlah data terlalu sedikit untuk dilakukan clustering dengan ' . $k . ' cluster.');
        }


        $centroids = collect($points->random($k)->pluck('features'));

        $maxIterations = 100;
        for ($iteration = 0; $iteration < $maxIterations; $iteration++) {
            $clusters = [];
            foreach ($points as $point) {
                $distances = $centroids->map(function ($centroid) use ($point) {
                    return sqrt(
                pow($centroid[0] - $point['features'][0], 2) +
                pow($centroid[1] - $point['features'][1], 2) +
                pow($centroid[2] - $point['features'][2], 2)
                  );
                });
                $closest = $distances->search($distances->min());
                $clusters[$closest][] = $point;
            }   

            $newCentroids = collect();
            foreach ($clusters as $cluster) {
                $count = count($cluster);
                $sum = [0, 0, 0];
                foreach ($cluster as $point) {
                    foreach ($point['features'] as $i => $val) {
                        $sum[$i] += $val;
                    }
                }
                $newCentroids->push([
                    $sum[0] / $count,
                    $sum[1] / $count,
                    $sum[2] / $count,
                ]);
            }

            if ($newCentroids == $centroids) {
                break;
            }
            $centroids = $newCentroids;
    }

    foreach ($clusters as $clusterIndex => $items) {
        foreach ($items as $item) {
            DB::table('data_pemilih')
                ->where('id', $item['id'])
                ->update(['cluster' => $clusterIndex]);
        }
    }

    $user = auth()->user();
    if ($user && is_numeric($user->id)) {
        UserActivity::create([
            'user_id' => $user->id,
            'activity' => 'Melakukan proses clustering dengan algoritma K-Means pada data pemilih untuk jenis pemilu ' . $request->jenis_pemilu,
    ]);

    }

    $hasilClustering = DB::table('data_pemilih')
        ->where('jenis_pemilu', $request->jenis_pemilu)
        ->select('kecamatan', 'dpt_total', 'suara_total', 'partisipasi', 'cluster')
        ->get();
    
    session([
        'jenis_pemilu' => $request->jenis_pemilu,
        'hasil_clustering' => $hasilClustering,
    ]);
    return redirect()->route('clustering.index', ['jenis_pemilu' => $request->jenis_pemilu])
                     ->with('success', 'Proses clustering berhasil dilakukan.');
    
}

public function export(Request $request)
{
    $jenisPemilu = $request->input('jenis_pemilu', 'Presiden');

    $data = DB::table('data_pemilih')
        ->select('kecamatan', 'dpt_total', 'suara_total', 'partisipasi', 'cluster')
        ->whereNotNull('cluster')
        ->get();

    $csv = "kecamatan,dpt_total,suara_total,partisipasi,cluster\n";

    foreach ($data as $row) {
        $csv = "{$row->kecamatan},{$row->dpt_total},{$row->suara_total},{$row->partisipasi},{$row->cluster}\n";
    }

    Storage::put('public/clustering_export.csv', $csv);

    return response()->download(storage_path('app/public/clustering_export.csv'));
}

}