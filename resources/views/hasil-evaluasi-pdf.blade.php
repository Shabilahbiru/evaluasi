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
        <div class="jenis">Jenis Pemilu: {{ session('jenis_pemilu') ?? '-' }}</div>
        <div class="tanggal">
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d/m/y') }}
        </div>
    </div>

    <h4>Ringkasan Statistik</h4>
    <ul>
        <li>Total Data: {{ $totalData }}</li>
        <li>Jumlah Cluster: {{ $jumlahCluster }}</li>
        <li>Cluster Terbesar: Cluster {{ $clusterTerbesar }}</li>
        <li>Rata-rata Partisipasi: {{ number_format($rataRataPartisipasi, 2) }}%</li>
    </ul>

    <h4>Distribusi Data per Cluster</h4>
    <table class="cluster-table">
        <thead>
            <tr>
                <th>Cluster</th>
                <th>Jumlah Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($labels as $index => $label)
            <tr>
                <td>{{ $label }}</td>
                <td>{{ $jumlahPerCluster[$index] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if (!empty($clusterKecamatanList))
    <h4>Daftar Kecamatan per Cluster:</h4>
    <table style="width: 100%; border-collapse: collapse;" border="1" cellpadding="8">
        <thead style="background-color: #f2f6fc;">
            <tr>
                <th style="text-align: left;">Cluster</th>
                <th style="text-align: left;">Daftar Kecamatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clusterKecamatanList as $item)
                <tr>
                    <td>Cluster {{ $item['cluster'] }}</td>
                    <td>{{ implode(', ', $item['kecamatan']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif


    <h4>Evaluasi</h4>
    <p>{!! $evaluasiKesimpulan !!}</p>

    <h4>Kesimpulan</h4>
    <p>{!! $kesimpulan !!}</p>

    @if (!empty($clusterDescriptions))
    <h4>Karakteristik Tiap Cluster</h4>
        <ul>
            @foreach ($clusterDescriptions as $desc)
                <li>{{ $desc }}</li>
            @endforeach
        </ul>
    @endif

    <hr style="margin-top: 40px;">   
    <p style="font-size: 12px; color: #555;">
    <strong>Catatan:</strong><br>
    Laporan ini dihasilkan berdasarkan hasil clustering data pemilih untuk jenis pemilu <strong>{{ session('jenis_pemilu') ?? 'Presiden' }}</strong>.
    Jika Anda ingin menghasilkan laporan untuk jenis pemilu lain, silakan lakukan proses clustering ulang terlebih dahulu.
    </p>
   
</body>
</html>