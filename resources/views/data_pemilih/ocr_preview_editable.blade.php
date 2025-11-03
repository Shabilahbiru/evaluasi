@extends('layouts.app')

@section('title', 'Preview Data Pemilih')

@section('content')
<div class="container">
    <h3>📋 Pratinjau & Koreksi Data Pemilih</h3>
    <form method="POST" action="{{ route('data-pemilih.scan.confirm') }}">
        @csrf

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Kecamatan</th>
                    <th>DPT Laki-laki</th>
                    <th>DPT Perempuan</th>
                    <th>DPT Total</th>
                    <th>Suara Sah</th>
                    <th>Suara Tidak Sah</th>
                    <th>Suara Total</th>
                    <th>Partisipasi (%)</th>
                    <th>Jenis Pemilu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($previewData as $index => $item)
                <tr>
                    @foreach (['kecamatan','dpt_laki_laki','dpt_perempuan','dpt_total','suara_sah','suara_tidak_sah','suara_total','partisipasi','jenis_pemilu'] as $field)
                    <td>
                        <input type="text" name="data[{{ $index }}][{{ $field }}]" class="form-control" value="{{ $item[$field] ?? '' }}">
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Simpan Semua</button>
    </form>
</div>
@endsection