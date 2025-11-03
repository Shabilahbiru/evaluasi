@extends('layouts.app')

@section('title', 'Proses Clustering')

@section('content')
<div class="welcome-box">

    @if (session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            {{ session('error') }}
        </div>
    @endif

    <h2 style="margin-bottom: 20px;">Proses Clustering Data Pemilih</h2>

<form action="{{ route('clustering.process') }}" method="POST">
    @csrf
    <a href="{{ route('hasil.evaluasi') }}" class="btn btn-primary" style="margin-bottom: 20px;">
    <i class="fas fa-poll"></i> Proses Evaluasi Partisipasi</a>
</form>

<h4>Hasil Clustering untuk Jenis Pemilu: <span style="color: #4f8ae2">{{ $jenisPemilu }}</span></h4>
@if (count($data) > 0)
<div class="card" style="overflow-x: auto;">
    <h5 style="margin-top: 2px; margin-bottom: 10px;">Tabel Hasil Clustering per Kecamatan</h5>
    <p style="font-weight: normal; font-size: 14px;">Tabel ini menunjukkan kecamatan yang telah dikelompokkan ke dalam cluster berdasarkan data pada data pemilih.</p>

    <table id="tabel-clustering" class="table">
        <thead>
            <tr>
                <th style="font-weight: 700; font-size: 17px;">No</th>
                <th style="font-weight: 700; font-size: 17px;">Kecamatan</th>
                <th style="font-weight: 700; font-size: 17px;">Cluster</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
            <tr>
                <td style="font-weight: normal;">{{ $index + 1 }}</td>
                <td style="font-weight: normal;">{{ $item->kecamatan }}</td>
                <td style="font-weight: 400;"><strong>Cluster {{ $item->cluster }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card" style="margin-top: 30px; padding: 20px;">
    {{-- <h4 style="margin-bottom: 20px;">Scatter Matrix Interaktif Antar Fitur</h4> --}}
    <div class="row d-flex align-items-center">
        <div class="col-md-8">
            <div class="card p-3 mt-2">
                <div id="scatterMatrixPlot" class="w-100" style="min-height: 400px; height: 100%;"></div>
            </div>
        </div>
        <div class="col-md-4 d-flex justify-content-center mt-3 mt-md-4 mt-lg-5">
            <div class="card" style="padding: 15px; border-top: 4px solid #4f8ae2;">
                <h5 style="margin-top: 12px;"><strong>Interpretasi Scatter Matrix</strong></h5>
                <p style="font-size: 16px; font-weight: normal;">
                    Visualisasi ini menunjukkan relasi antar fitur seperti jumlah DPT, total suara dan partisipasi.
                    Warna menandakan cluster hasil K-Means. Titik yang saling berdekatan menunjukkan kesamaan karakteristik antar kecamatan.
                    Semakin rapi pemisahan warnanya, semakin efektif clustering yang dilakukan.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin: 4px;">
<div class="card" style="margin-top: 20px; border-top: 5px solid #4f8ae2; margin-inline: 10px;">
    <h4>Distribusi data per Cluster</h4>
    <ul style="font-weight: normal; list-style-position: inside;">
        @foreach ($deskripsiPerCluster as $cluster => $info)
            <li>
            <strong>Cluster {{ $cluster }}:</strong> Memiliki {{ $jumlahPerCluster[$cluster] ?? 0 }} data, <br> 
            {{ $info['deskripsi'] }}
            </li>
        @endforeach
    </ul>
</div>

{{-- <div class="card mt-4" style="border-top: 5px solid #4f8ae2; padding: 20px;">
    <h4>Kategori Partisipasi per Cluster</h4>
    <ul>
        @foreach ($deskripsiPerCluster as $cluster => $info)
            <li><strong>Cluster {{ $cluster }} ({{ $info['kategori'] }})</strong>: {{ $info['deskripsi'] }}</li>
        @endforeach
    </ul>
</div> --}}

<div class="card" style="margin-top: 20px; border-top: 5px solid #4f8ae2; margin-inline: 10px;">
    <h4>Kesimpulan</h4>
    <p style="font-weight: normal;">{{ $kesimpulan }}</p>
</div>
</div>

<div class="card" style="margin-top: 30px;">
    <h4 style="margin-bottom: 20px;">Visualisasi Jumlah Data per Cluster</h4>
    <canvas id="clusterChart" height="400"></canvas>
</div>



@else 
<p>Belum ada hasil clustering. Silakan klik tombol di atas untuk memproses data.</p>
@endif
</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('clusterChart');
        if(!canvas) return;

        const ctx = canvas.getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'jumlah Data',
                    data: {!! json_encode($jumlahPerCluster) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {display: false},
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Jumlah: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {display: true, text: 'Jumlah Data' }
                    },
                    x: {
                        title: {display: true, text:'Cluster'}
                    }
                }
            }
        });
    });
</script>

<script src="https://cdn.plot.ly/plotly-2.27.1.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const raw = @json($fiturData);

        const dpt = raw.map(d => d.dpt_total);
        const suara = raw.map(d => d.suara_total);
        const partisipasi = raw.map(d => d.partisipasi);
        const cluster = raw.map(d => d.cluster);
        const kecamatan = raw.map(d => d.kecamatan);

        const data = [{
            type: 'splom',
            dimensions: [
                {label: 'DPT Total', values: dpt},
                {label: 'Suara Total', values: suara},
                {label: 'Partisipasi', values: partisipasi}
            ],
            text: kecamatan.map((nama, i) => `Kecamatan: ${nama}<br>Cluster: ${cluster[i]}`),
            customdata: kecamatan,
            hovertemplate: '%{text}<extra></extra>',
            marker: {
                color: cluster,
                colorscale: 'OrRd',
                showscale: true,
                size: 8,
                line: { color: 'white', width: 0.5 }
            }
        }];

        const layout = {
            height: 600,
            hovermode: 'closest',
            title: 'Scatter Matrix Berdasarkan Cluster', 
            dragmode: 'select'
        };

        const plotDiv = document.getElementById('scatterMatrixPlot');
        Plotly.newPlot(plotDiv, data, layout, { responsive: true });

        plotDiv.on('plotly_click', function (event) {
            const kecamatan = event.points[0].customdata;
            if (kecamatan) {
                window.open(`/hasil-evaluasi?kecamatan=${encodeURIComponent(kecamatan)}`, '_blank');
            }
        });

        window.addEventListener('resize', () => Plotly.Plots.resize(plotDiv));
        setTimeout(() => Plotly.Plots.resize(plotDiv), 500);
    });
</script>

@endpush