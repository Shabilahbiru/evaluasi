<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Hasil Evaluasi Bakesbangpol Kota Bandung</title>
    <style>
        body {
            font-family: sans-serif; 
            font-size: 14px;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            display: flex;
            align-items: center;
            border-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            width: 60px;
            height: auto;
            margin-right: 15px;
        }

        .header-title {
            flex: 1;
        }

        .header-title h2 {
            margin: 0;
            font-size: 18px;
            color: #D32F2F;
        }

        .header-title h3 {
            margin: o;
            font-size: 14px;
            color: #555;
        }

        .tanggal {
            font-size: 12px;
            color: #777;
        }

        .section {
            margin-bottom: 30px;
        }

        .summary {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 10px 15px;
            flex: 1;
            min-width: 150px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 8px 12px;
            border: 1px solid #bbb;
            text-align: center;
        }

        th {
            background-color: #e3f2fd;
            color: #333;
        }

        .highlight {
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('img/Bakesbangpol.png') }}" alt="Logo Bakesbangpol">
        <div class="header-title">
             <h2>Hasil Evaluasi Partisipasi Pemilih</h2>
             <h3>Bakesbangpol Kota Bandung</h3>
             <h4 style="text-align: center; margin-bottom: 20px;">
                @if(isset($kecamatan))
                    wilayah: <strong>{{ $kecamatan }}</strong>
                @else
                    <em>Seluruh Wilayah Kota Bandung</em>
                @endif
             </h4>
        </div>
        <div class="tanggal">
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d/m/y') }}
        </div>
    </div>

   
    <div class="section summary">
        <div class="card">Total Data: <span class="highlight">{{ $totalData }}</span></div>
        <div class="card">Jumlah Cluster: <span class="highlight">{{ $jumlahCluster }}</span></div>
        <div class="card">Cluster Terbesar: <span class="higlight">Cluster {{ $clusterTerbesar }}</span></div>
        <div class="card">Rata-rata Partisipasi: <span class="highlight">{{ $rataRataPartisipasi }}%</span></div>
    </div>

    <div class="section">
        <h3>Distribusi Data per Cluster: </h3>
        <table>
            <thead>
                <tr>
                    <th>Cluster</th>
                    <th>Jumlah Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($labels as $i =>$label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $jumlahPerCluster[$i] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Evaluasi</h3>
        <p>{!! $evaluasiKesimpulan !!}</p>
    </div>

    <div class="section">
        <h3>Kesimpulan Umum</h3>
        <p>{{ $kesimpulan }}</p>
    </div>
</body>
</html>