@extends('layouts.app')

@section('title', 'Hasil Evaluasi')

@section('content')
<div class="welcome-box">
    <h2 style="margin-bottom: 20px;">Hasil Evaluasi Program Bakesbangpol</h2>

  <form action="{{ route('hasil-evaluasi.export') }}" method="GET" target="_blank" style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
    <select name="kecamatan" style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
        <option value="">-- Seluruh Wilayah--</option>
        @foreach($daftarKecamatan as $kec)
            <option value="{{ $kec }}">{{ $kec }}</option>
        @endforeach
    </select>

    <button type="submit" formaction="{{ route('hasil-evaluasi.preview') }}" class="btn-download">👁</button>
    <button type="submit" class="btn-download">📄 Export PDF</button>   
</form>
<a href="{{ route('hasil-evaluasi.export-semua') }}" class="btn-download" style="margin-bottom: 20px;">📦 Export Semua Kecamatan (ZIP)</a>


    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        <div class="card p-3 d-flex align-items-center" style="min-width: 250px;">
            <i class="fas fa-database fa-2x text-primary me-3"></i>
            <div>
                <div><strong>Total Data: </strong></div>
                <div>{{ $totalData }}</div>
            </div>
        </div>

        <div class="card p-3 d-flex align-items-center" style="min-width: 250px;">
            <i class="fas fa-layer-group fa-2x text-success me-3"></i>
            <div>
                <div><strong>Jumlah Cluster:</strong></div>
                <div>{{ $jumlahCluster }}</div>
            </div>  
        </div>

        <div class="card p-3 d-flex align-items-center" style="min-width: 250px;">
            <i class="fas fa-chart-line fa-2x text-danger me-3"></i>
            <div>
                <div><strong>Cluster Tertnggi: </strong></div>
                <div>Cluster {{ $clusterTerbesar }}</div>
            </div>
        </div>
        

        <div class="card p-3 d-flex align-items-center" style="min-width: 250px;">
            <i class="fas fa-percent fa-2x text-warning me-3"></i>
            <div>
                <div><strong>Rata-rata Partisipasi:</strong></div>
                <div>{{ number_format($rataRataPartisipasi, 2) }}%</div>
            </div>
        </div>    
    </div>

    <div class="card" style="margin-bottom: 30px;">
        <h4 style="margin-bottom: 20px;">Rata-rata Partisipasi Pemilih per Kecamatan</h4>
        <canvas id="partisipasiLineChart" height="400"></canvas>
    </div>

    <div class="row">    
    <div class="col-sm-4">
        <div class="card h-100" style="margin-top: 0;">
        <h4>Evaluasi Sistem</h4>
        <p style="font-weight: normal;">{!! $evaluasiKesimpulan !!}</p>
        </div>
    </div>
    <div class="col-sm-8">
            <div class="card h-100" style="margin-top: 0;">
        <h4>Kesimpulan</h4>
        <p style="font-weight: normal;">{!! $kesimpulan !!}</p>
        </div>
    </div>
    </div>

    <div class="card" style="margin-top: 30px;">
        <h4 style="margin-bottom: 20px;">Perbandingan Suara Sah vs Suara Tidak Sah</h4>
        <canvas id="suaraBarChart" height="400"></canvas>
    </div>

    <div class="row mt-4">
        <!-- Kolom Pie Chart -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="text-center">
                <h5 style="margin-bottom: 15px;">Visualisasi Jumlah Data per Cluster</h5>
                <canvas id="pieChart" width="300" height="300"></canvas> {{-- Ukuran dikecilkan --}}
            </div>
        </div>

        <!-- Kolom Kesimpulan Intervensi -->
        <div class="col-md-6">
            <div class="card-body text-start">
            <h5 class="card-title text-danger text-center">
            <i class="fas fa-exclamation-circle"></i> Intervensi Wilayah</h5>

        @php
            $wilayahKurang = collect($rankingKecamatan)
                ->where('kategori', 'kurang')
                ->pluck('kecamatan')
                ->toArray();
        @endphp

        @if(count($wilayahKurang) > 0)
            <p style="text-align: justify;">
                Berdasarkan hasil <strong>clustering partisipasi pemilih</strong>, terdapat <strong>{{ count($wilayahKurang) }}</strong> kecamatan yang tergolong dalam 
            <span class="badge bg-danger">Cluster Partisipasi Kurang</span>.
                Wilayah ini berpotensi membutuhkan <em>intervensi khusus</em> atau <em>pendidikan politik tambahan</em> dari pihak Bakesbangpol.</p>

            <p style="text-align: justify;">
                Kecamatan-kecamatan tersebut antara lain: 
            <strong>{{ implode(', ', $wilayahKurang) }}.</strong></p>

            <p style="text-align: justify;">
                Disarankan dilakukan program peningkatan partisipasi pemilih di wilayah tersebut, seperti penyuluhan, pelibatan tokoh masyarakat, atau inovasi kegiatan.
            </p>
        @else
            <p class="text-muted">
                Seluruh wilayah menunjukkan tingkat partisipasi cukup hingga tinggi. Tidak ada wilayah yang tergolong dalam kategori rendah.
            </p>
        @endif
        </div>
    </div>
</div>
</div>

<div class="container my-4">
    <div class="d-flex justify-content-end">
        <a href="{{ route('clustering.index', ['jenis_pemilu' => $jenisPemilu]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Clustering
        </a>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('partisipasiLineChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labelsKecamatan) !!},
                datasets: [{
                    label: 'Rata-rata Partisipasi (%)',
                    data: {!! json_encode($values) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Partisipasi (%)'
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
    });

    const pieCtx = document.getElementById('pieChart');
    if (pieCtx) {
        const clusterKategori = @json($clusterKategori);
        const pieLabels = Object.keys(clusterKategori).map(i => `Cluster ${i} (${clusterKategori[i]})`);

        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: {!! json_encode($jumlahPerCluster) !!},
                    backgroundColor: ['#3498db', '#e67e22', '#2ecc71', '#9b59b6', '#f1c40f']
                }]
            },
            options: {
                responsive: true
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const ctxSuara = document.getElementById('suaraBarChart').getContext('2d');

        new Chart(ctxSuara, {
            type: 'bar',
            data: {
                labels: {!! json_encode($suaraLabels) !!},
                datasets: [
                    {
                        label: 'Suara Sah',
                        data: {!! json_encode($suaraSah) !!},
                        backgroundColor: 'rgba(46, 204, 113, 0.6)',
                        borderColor: 'rgba(46, 204, 113, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Suara Tidak Sah',
                        data: {!! json_encode($suaraTidakSah) !!},
                        backgroundColor: 'rgba(231, 76, 60, 0.6)',
                        borderColor: 'rgba(231, 76, 60, 1)', 
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Suara'
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
    });

     const ctxRanking = document.getElementById('rankingChart').getContext('2d');
    const rankingData = @json($rankingKecamatan);

    const labels = rankingData.map(item => item.kecamatan);
    const values = rankingData.map(item => item.partisipasi);
    const clusterLabels = rankingData.map(item => item.kategori);
    
    const clusterColors = {
        'Tinggi': 'rgba(40, 167, 69, 0.8)',
        'Cukup': 'rgba(255, 193, 7, 0.8)',
        'Rendah': 'rgba(220, 53, 69, 0.8)'
    };

    const backgroundColors = clusterLabels.map(kat => clusterColors[kat]);

    new Chart(ctxRanking, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Partisipasi (%)',
                data: values,
                backgroundColor: backgroundColors,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const index = context.dataIndex;
                            return `${rankingData[index].kecamatan}: ${rankingData[index].partisipasi}% | Cluster: ${rankingData[index].cluster} (${rankingData[index].kategori})`;
                        }
                    }
                },
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Partisipasi (%)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Kecamatan'
                    }
                }
            }
        }
    });

</script>
@endsection


