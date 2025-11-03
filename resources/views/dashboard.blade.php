@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="welcome-box" style="margin-bottom: 12px;">
        <h2>Halo, {{ Auth::user()->username }}! 👋</h2>
    <div class="welcome-section">
        <div class="card-body" style="margin-top: 8px;">
            <h3 style="margin-bottom: 5px;">Selamat Datang di Sistem SAPALIH Kota Bandung</h3>
            <p>Sistem SAPALIH Kota Bandung mendukung evaluasi program pendidikan politik yang diadakan oleh Bakesbangpol Kota Bandung. 
            Melalui sistem ini, evaluasi berbasis data mining dilakukan secara transparan dan akurat guna mendukung untuk peningkatan partisipasi pemilih dalam pemilu.</p>
        </div>
    </div>
    
    <div class="row mt-0 mb-0">

        {{-- Petunjuk Penggunaan Sistem --}}

    <div class="col-md-6 mb-0">
            <div class="card h-80" style="border-top: 5px solid #4f8ae2; ">
                {{-- <h5 class="card-title">📝<br> Penggunaan Sistem</h5> --}}
                <div id="accordion">
                    <div class="card">
                        <div class="card-header">
                        <a class="card-link" data-toggle="collapse" href="#collapseOne" style="font-size: 18px">📝 Petunjuk Penggunaan Sistem SAPALIH </a>
                        </div>
                        <div id="collapseOne" class="collapse" data-parent="#accordion">
                        <div class="card-body" style="font-weight: normal;">
                        <p class="card-text" style="font-weight: normal;">
                        Sistem ini digunakan untuk mengevaluasi partisipasi pemilih di Kota Bandung berdasarkan data pemilih yang diperoleh dari KPU.
                        <ul class="card-list" style="font-weight: normal;">
                            <li>Gunakan menu di sidebar, data pemilih dapat dikelola melalui menu "Data Pemilih"</li>
                            <li>Melakukan proses clustering menggunakan algoritma K-Means di menu "Clustering"</li>
                            <li>Membaca laporan hasil evaluasi secara visual di menu "Hasil Evaluasi"</li>
                        </ul>
                        </p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aktivitas Terakhir --}}
        <div class="col-md-6 mb-0">
            <div class="card h-80" style="border-top: 5px solid #4f8ae2; ">
                {{-- <h5 class="card-title">📌<br> Aktivitas Terakhir</h5> --}}
                <div id="accordion">
                    <div class="card">
                        <div class="card-header">
                        <a class="card-link" data-toggle="collapse" href="#collapseOne" style="font-size: 18px;">📌 Riwayat Aktivitas Pengguna </a>
                        </div>
                        <div id="collapseOne" class="collapse" data-parent="#accordion">
                        <div class="card-body" style="font-weight: normal;">
                        <ul class="card-text" style="font-weight: normal;">
                        @forelse ($aktivitasTerakhir as $aktivitas)
                            <li>{{ $aktivitas->activity }} <br>
                                <small class="text-muted">{{ $aktivitas->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item">Belum ada aktivitas</li>
                        @endforelse
                        </ul>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

</div>

@if(isset($jenisPemilu))
  <div class="alert alert-info text-center mt-3" style="font-size: 16px;">
    Menampilkan data berdasarkan <strong>Jenis Pemilu: {{ $jenisPemilu }}</strong>
  </div>
@endif

<div class="containe-fluid">

    <div class="row card-summary mt-0">
         <div class="card p-4 m-2">
            <div class="mb-2">
            <i class="fas fa-map-marked-alt fa-2x text-primary"></i>
            </div>
            <h3>Jumlah Kecamatan</h3>
            <p>{{ number_format($jumlahKecamatan) }}</p>
            </div>
       
        
        <div class="card p-4 m-2">
            <div class="mb-2">
                <i class="fas fa-users fa-2x text-success"></i>
            </div>
            <h3>Total Pemilih</h3>
            <p>{{ number_format($totalPemilih) }}</p>
        </div>

        <div class="card p-4 m-2">
            <div class="mb-2">
                <i class="fas fa-male fa-2x text-info"></i>
            </div>
            <h3>DPT Laki-laki</h3>
            <p>{{ number_format($dptLakilaki) }}</p>
        </div>

        <div class="card p-4 m-2">
            <div class="mb-2">
                <i class="fas fa-female fa-2x text-danger"></i>
            </div>
            <h3>DPT Perempuan</h3>
            <p>{{ number_format($dptPerempuan) }}</p>
        </div>
    </div>

    <div class="grafik-wrapper">
    <div class="card mb-4">
        <h3 style="margin-bottom: 20px;">Scatter Matrix Berdasarkan Cluster</h3>
        <div id="scatterMatrixPlot" style="width: 100%; height: 500px;"></div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 20px;">Grafik Partisipasi per Kecamatan</h3>
        <canvas id="barChart" height="500"></canvas>
    </div>
    </div>


</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
@endpush

@push('scripts')
<script src="https://cdn.plot.ly/plotly-2.27.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const fiturData = @json($fiturData);
    const partisipasiData = @json($partisipasiPerKecamatan);

    const barLabels = partisipasiData.map(item => item.kecamatan);
    const barValues = partisipasiData.map(item => item.total_partisipasi);

    const suaraSah = partisipasiData.map(item => item.total_suara_sah);
    const suaraTidakSah = partisipasiData.map(item => item.total_suara_tidak_sah);

    const dpt = fiturData.map(d => d.dpt_total);
    const suara = fiturData.map(d => d.suara_total);
    const partisipasi = fiturData.map(d => d.partisipasi);
    const cluster = fiturData.map(d => d.cluster);
    const kecamatan = fiturData.map(d => d.kecamatan);  // ← Tambah ini

    const splomData = [{
        type: 'splom',
        dimensions: [
            {label: 'DPT Total', values: dpt},
            {label: 'Suara Total', values: suara},
            {label: 'Partisipasi', values: partisipasi}
        ],
        text: kecamatan.map((kec, i) => `Kecamatan: ${kec}<br>Cluster: ${cluster[i]}`),                     // ← nama kecamatan untuk hover
        customdata: kecamatan,              // ← data untuk klik
        marker: {
            color: cluster,
            colorscale: 'OrRd',
            showscale: true,
            size: 8,
            line: { color: 'white', width: 0.5 }
        },
        hovertemplate: '%{text}<extra></extra>'  // ← tampilkan nama saat hover
    }];

    const layout = {
        height: 600,
        hovermode: 'closest',
        title: 'Cluster Berdasarkan Data Pemilih',
        dragmode: 'select'
    };

    Plotly.newPlot('scatterMatrixPlot', splomData, layout, {responsive: true});
    window.addEventListener('resize', () => {
        Plotly.Plots.resize(document.getElementById('scatterMatrixPlot'));
    });
    setTimeout(() => {
        Plotly.Plots.resize(document.getElementById('scatterMatrixPlot'));
    }, 500);

    // ⛳ Saat titik diklik, redirect ke hasil evaluasi wilayah
    document.getElementById('scatterMatrixPlot')
        .on('plotly_click', function(data) {
            const namaKecamatan = data.points[0].customdata;
            const url = `/hasil-evaluasi?kecamatan=${encodeURIComponent(namaKecamatan)}`;
            window.location.href = url;
        });



    new Chart(document.getElementById('barChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [{
                label: 'Total Partisipasi',
                data: barValues,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                                const i = context.dataIndex;
                                const partisipasi = context.parsed.y;
                                const sah = suaraSah[i];
                                const tidakSah = suaraTidakSah[i];
                                    return [
                                    `Partisipasi: ${partisipasi.toFixed(2)}%`,
                                    `Suara Sah: ${sah}`,
                                    `Suara Tidak Sah: ${tidakSah}`
                            ];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Partisipasi'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Kecamatan'
                    }
                }
            }
        }
    });
</script>
@endpush

