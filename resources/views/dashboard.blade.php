@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="welcome-box">
        <h2>Halo, {{ Auth::user()->username }}! 👋</h2>
    <div class="welcome-section">
        <div class="card-body" style="margin-top: 8px;">
            <h3 style="margin-bottom: 5px;">Selamat Datang di Sistem Evaluasi Bakesbangpol</h3>
            <p>Bakesbangpol Kota Bandung mendukung program sosial-politik berbasis data yang akurat dan transparan.
            Badan Kesatuan Bangsa dan Politik (Bakesbangpol) Kota Bandung berperan dalam mendukung program-program
            sosial-politik termasuk peningkatan partisipasi pemilih dalam pemilu. Melalui sistem ini, evaluasi
            berbasis data mining dilakukan secara transparan dan akurat.</p>
        </div>
    </div>
    </div>
</div>

<div class="containe-fluid">
    <div class="row">

        {{-- Petunjuk Penggunaan Sistem --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100" style="border-top: 5px solid #4f8ae2;">
                <div class="card-body">
                    <h5 class="card-title">📝<br> Penggunaan Sistem</h5>
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

        {{-- Aktivitas Terakhir --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100" style="border-top: 5px solid #4f8ae2;">
                <div class="card-body">
                    <h5 class="card-title">📌<br> Aktivitas Terakhir</h5>
                    <ul class="card-text" style="font-weight: normal;">
                        @forelse ($aktivitasTerakhir as $aktivitas)
                            <li>{{ $aktivitas->kegiatan }} <br>
                                <small>{{ $aktivitas->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li>Belum ada aktivitas</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Program Kegiatan Bakesbangpol --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100" style="border-top: 5px solid #4f8ae2;">
                <h5 class="card-title"> Program Kegiatan Bakesbangpol </h5>
                <div id="accordion">
                    <div class="card">
                        <div class="card-header">
                        <a class="card-link" data-toggle="collapse" href="#collapseOne"> Pendidikan Politik </a>
                        </div>
                        <div id="collapseOne" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                            Pendidikan politik adalah usaha yang sadar untuk mengubah proses sosialisasi politik masyarakat sehingga mereka memahami dan menghayati betul nilai-nilai yang terkandung dalam sistem politik yang ideal yang hendak dibangun.
                        </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                        <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo"> # Beberapa Kegiatan Pendidikan Politik oleh Poldagri </a>
                        </div>
                        <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                            <ol style="text-align: left;">
                                <li>Diskusi Politik "Perkembangan dan Dinamika Sosial Politik Masyarakat di Menjelang Pemilu dan Pilkada Serentak 2024" Kota Bandung.</li>
                                <li>Sosialisasi penyelenggaraan pemilu dan pilkada serentak tahun 2024 bagi tenaga pengajar PKN pada SMA/SMK/MA se-Kota Bandung.</li>
                                <li>Pendidikan politik bagi perempuan "Peran Perempuan Dalam Menyongsong Sukses Pemilu Damai 2024".</li>
                            </ol>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>


    <div class="row card-summary">
         <div class="card p-3 m-2">
            <h3>Jumlah Kecamatan</h3>
            <p>{{ $jumlahKecamatan }}</p>
        </div>
        
        <div class="card p-3 m-2">
            <h3>Total Pemilih</h3>
            <p>{{ $totalPemilih }}</p>
        </div>

        <div class="card p-3 m-2">
            <h3>DPT Laki-laki</h3>
            <p>{{ $dptLakilaki }}</p>
        </div>

        <div class="card p-3 m-2">
            <h3>DPT Perempuan</h3>
            <p>{{ $dptPerempuan }}</p>
        </div>
    </div>

    <div class="grafik-wrapper">
    <div class="card">
        <h3 style="margin-bottom: 20px;">Grafik Sebaran Jumlah Pemilih vs Partisipasi</h3>
        <canvas id="scatterChart" height="400"></canvas>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 20px;">Grafik Partisipasi per Kecamatan</h3>
        <canvas id="barChart" height="400"></canvas>
    </div>
    </div>


</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const scatterDataRaw = @json($scatterData);
    const partisipasiData = @json($partisipasiPerKecamatan);

    const barLabels = Object.keys(partisipasiData);
    const barValues = Object.values(partisipasiData);

    const scatterData = scatterDataRaw.map(item => ({
        x: item.total_pemilih,
        y: item.total_partisipasi,
        label: item.kecamatan
    }));

        new Chart(document.getElementById('scatterChart').getContext('2d'), {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Kecamatan',
                data: scatterData,
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                pointRadius: 6
            }]
        },

        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.raw.label || '';
                            return `${label}: (${context.raw.x}, ${context.raw.y})`;
                        }
                    }
                }
            },

            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Total Pemilih'
                    },

                    beginAtZero: true
                },

                y: {
                    title: {
                        display: true,
                        text: 'Total Partisipasi'
                    },

                    beginAtZero: true
                }
            }
        }
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
                        label: context => 'Partisipasi' + context.parsed.y
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

