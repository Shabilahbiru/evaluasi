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
        <div class="card">Total Data: {{ $totalData }}</div>
        <div class="card">Jumlah Cluster: {{ $jumlahCluster }}</div>
        <div class="card">Cluster Tertnggi: Cluster {{ $clusterTerbesar }}</div>
        <div class="card">Rata-rata Partisipasi: {{ number_format($rataRataPartisipasi, 2) }}%</div>    
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
        <p style="font-weight: normal;">{{ $kesimpulan }}</p>
        </div>
    </div>
    
    </div>

    <div class="card" style="margin-top: 30px;">
        <h4 style="margin-bottom: 20px;">Perbandingan Suara Sah vs Suara Tidak Sah</h4>
        <canvas id="suaraBarChart" height="400"></canvas>
    </div>

    <div class="card" style="margin-top: 30px;">
        <h4 style="margin-bottom: 20px;">Visualisasi Jumlah Data per Cluster</h4>
        <canvas id="pieChart" width="300" height="300" style="max-width: 400px; margin: auto;" ></canvas>
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
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    data: {!! json_encode($jumlahPerCluster) !!},
                    backgroundColor: ['#3498db', '#e67e22', '#2ecc71', '#9b59b6', '#f1c40f']
                }]
            },
            options: {
                responsive: true
            }
        })
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
</script>
@endsection


