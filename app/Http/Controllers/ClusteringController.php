<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClusteringController extends Controller
{
    public function index()
    {
        $data =DB::table('data_pemilih')->whereNotNull('cluster')->get();
        $totalData = $data->count();
        $jumlahCluster = $data->pluck('cluster')->unique()->count();
        $jumlahPerCluster = $data->groupBy('cluster')->map(function($group) {
            return $group->count();
        });

        $clusterTerbesar = $jumlahPerCluster->sortDesc()->keys()->first();

        $kesimpulan = "Berdasarkan hasil clustering, Cluster {$clusterTerbesar} merupakan kelompok dengan jumlah data terbanyak. 
        Hal ini menunjukkan bahwa sebagian besar wilayah memiliki karakteristik yang serupa dalam hal partisipasi pemilih.";

        return view('clustering', [
            'data' => $data,
            'totalData' => $totalData,
            'jumlahCluster' => $jumlahCluster,
            'clusterTerbesar' => $clusterTerbesar,
            'jumlahPerCluster' => $jumlahPerCluster->values(), // e.g. [10, 15, 5]
            'labels' => $jumlahPerCluster->keys()->map(fn($i) => "Cluster $i"),
            'kesimpulan' => $kesimpulan
        ]);
    }

    public function process()
    {
        $data = DB::table('data_pemilih')->select('id', 'dpt_total', 'suara_total', 'partisipasi')->get();
        $points = $data->map(function($item) {
            return [
                'id' => $item->id,
                'features' => [(float)$item->dpt_total, (float)$item->suara_total, (float)$item->partisipasi],   
            ];
        });

        $k = 3;
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

    return redirect()->route('clustering.index')->with('success', 'Proses clustering berhasil dilakukan.');
}

}