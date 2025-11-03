@extends('layouts.app')

@section('title', 'Preview Hasil OCR')

@section('content')
<div class="container">
    <h3 class="mb-4">Preview Hasil OCR Scan</h3>

    <form method="POST" action="{{ route('data-pemilih.scan.confirm') }}">
        @csrf
        <input type="hidden" name="data" value="{{ json_encode($previewData) }}">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kecamatan</th>
                    <th>DPT L</th>
                    <th>DPT P</th>
                    <th>DPT Total</th>
                    <th>Sah</th>
                    <th>Tidak Sah</th>
                    <th>Suara Total</th>
                    <th>Partisipasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($previewData as $row)
                <tr>
                    <td>{{ $row['kecamatan'] }}</td>
                    <td>{{ $row['dpt_laki_laki'] }}</td>
                    <td>{{ $row['dpt_perempuan'] }}</td>
                    <td>{{ $row['dpt_total'] }}</td>
                    <td>{{ $row['suara_sah'] }}</td>
                    <td>{{ $row['suara_tidak_sah'] }}</td>
                    <td>{{ $row['suara_total'] }}</td>
                    <td>{{ $row['partisipasi'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Simpan ke Database</button>
        <a href="{{ route('data-pemilih.create') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection