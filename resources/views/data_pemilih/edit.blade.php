@extends('layouts.app')

@section('title', 'Edit Data Pemilih')

@section('content')
<div class="form-container">
<div class="welcome-box">
    <h4 style="margin-bottom: 20px text-align: center;">Edit Data Pemilih</h4>

    @if ($errors->any())
    <div style="background-color: #f87da; color: #721c24; padding: 10px; border-radius: 6px; margin-bottom: 5px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('data-pemilih.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('data_pemilih._form', ['data' => $data])
        <div style="margin-top: 1px; display: flex; gap: 10px;">
            <button type="submit" class="btn-tambah">Simpan Perubahan</button>
            <a href="{{ route('data-pemilih.index') }}" class="btn-upload" style="text-align: center; display: inline-block;">Kembali</a>
        </div>
    </form>

</div>
</div>

<script>
    function hitungPartisipasi() {
        const dptL = parseInt(document.getElementById('dpt_laki_laki').value) || 0;
        const dptP = parseInt(document.getElementById('dpt_perempuan').value) || 0;
        const suaraSah = parseInt(document.getElementById('suara_sah').value) || 0;
        const suaraTidakSah = parseInt(document.getElementById('suara_tidak_sah').value) || 0;

        const dptTotal = dptL + dptP;
        const suaraTotal = suaraSah + suaraTidakSah;
        const partisipasi = dptTotal > 0 ? (suaraTotal / dptTotal) * 100 : 0;

        document.getElementById('dpt_total').value = dptTotal;
        document.getElementById('suara_total').value = suaraTotal;
        document.getElementById('partisipasi').value = partisipasi.toFixed(2);
    }

    document.querySelectorAll('#dpt_laki_laki, #dpt_perempuan, #suara_sah, #suara_tidak_sah').forEach(input => {
        input.addEventListener('input', hitungPartisipasi);
    });

    window.onload = hitungPartisipasi;
</script>

@endsection 