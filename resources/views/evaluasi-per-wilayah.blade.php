<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Evaluasi Per Kecamatan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            padding: 20px;
            color: #333;
        }

        h2, h3 {
            text-align: center;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 8px 12px;
            border: 1px solid #aaa;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .highlight {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Laporan Evaluasi Partisipasi Pemilih</h2>
    <h3>Kecamatan: {{ $kecamatan }}</h3>
    <p class="info">
        Tanggal Cetak: {{ $tanggal }}<br>
        Jenis Pemilu: <strong>{{ $jenis_pemilu }}</strong><br>
        Wilayah ini termasuk dalam <strong>Cluster {{ $clusterWilayah }}</strong>
    </p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kecamatan</th>
                <th>DPT Laki-laki</th>
                <th>DPT Perempuan</th>
                <th>DPT Total</th>
                <th>Suara Sah</th>
                <th>Suara Tidak Sah</th>
                <th>Suara Total</th>
                <th>Partisipasi (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->kecamatan }}</td>
                <td>{{ $item->dpt_laki_laki }}</td>
                <td>{{ $item->dpt_perempuan }}</td>
                <td>{{ $item->dpt_total }}</td>
                <td>{{ $item->suara_sah }}</td>
                <td>{{ $item->suara_tidak_sah }}</td>
                <td>{{ $item->suara_total }}</td>
                <td>{{ $item->partisipasi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-top: 30px;">Kesimpulan</h3>
    <p>{!! $kesimpulan !!}</p>

    @if (!empty($clusterDescriptions))
        <h4 style="margin-top: 20px;">Karakteristik Cluster {{ $clusterWilayah }}</h4>
        <ul>
            @foreach ($clusterDescriptions as $desc)
                <li>{{ $desc }}</li>
            @endforeach
        </ul>
    @endif

    
</body>
</html>