@extends('layouts.app')

@section('title', 'Proses Clustering')

@section('content')
<div class="welcome-box">
    <h2 style="margin-bottom: 20px;">Proses Clustering Data Pemilih</h2>

<form action="{{ route('clustering.process') }}" method="POST">
    @csrf
    <button type="submit" class="btn-upload" style="margin-bottom: 20px;">🔍 Proses Clustering</button>
</form>

@if (count($data) > 0)
<div class="card" style="overflow-x: auto;">
    <table id="tabel-clustering" class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kecamatan</th>
                <th>Kelurahan</th>
                <th>Cluster</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
            <tr>
                <td style="font-weight: normal;">{{ $index + 1 }}</td>
                <td style="font-weight: normal;">{{ $item->kecamatan }}</td>
                <td style="font-weight: normal;">{{ $item->kelurahan }}</td>
                <td style="font-weight: 500;"><strong>Cluster {{ $item->cluster }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="row" style="margin: 4px;">
<div class="card" style="margin-top: 20px; border-top: 5px solid #4f8ae2; margin-inline: 10px;">
    <h4>Distribusi data per Cluster</h4>
    <ul style="font-weight: normal; list-style-position: inside;">
        @foreach ($labels as $index => $label)
        <li>{{ $label }}: {{ $jumlahPerCluster[$index] }} data</li>
        @endforeach
    </ul>
</div>

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

@endpush