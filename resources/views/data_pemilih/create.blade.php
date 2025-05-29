@extends('layouts.app')

@section('title', 'Tambah Data Pemilih')

@section('content')
<div class="form-container">
<div class="card">
    <h4 style="margin-bottom: 20px">Tambah Data Pemilih</h4>

    @if ($errors->any())
    <div style="color: red; margin-bottom: 15px;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{  $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('data-pemilih.store') }}" method="POST" class="form-data" autocomplete="off">
        @csrf
        <div class="form-group" style="margin-bottom: 2px;">
        <label for="kecamatan">Kecamatan</label>
        <input type="text" name="kecamatan" id="kecamatan" class="form-control" placeholder="Masukkan Kecamatan" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="kelurahan">Kelurahan</label>
        <input type="text" name="kelurahan" id="kelurahan" class="form-control" placeholder="Masukkan Kelurahan" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="dpt_laki_laki">DPT Laki-laki</label>
        <input type="number" name="dpt_laki_laki" id="dpt_laki_laki" class="form-control" placeholder="Masukkan DPT Laki-laki" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="dpt_perempuan">DPT Perempuan</label>
        <input type="number" name="dpt_perempuan" id="dpt_perempuan" class="form-control" placeholder="Masukkan DPT Perempuan" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="dpt_total">DPT Total</label>
        <input type="number" name="dpt_total" id="dpt_total" class="form-control" placeholder="Masukkan DPT Total" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="suara_sah">Suara Sah</label>
        <input type="number" name="suara_sah" id="suara_sah" class="form-control" placeholder="Masukkan Suara Sah" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="suara_tidak_sah">Suara Tidak Sah</label>
        <input type="number" name="suara_tidak_sah" id="suara_tidak_sah" class="form-control" placeholder="Masukkan Suara Tidak Sah" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="suara_total">Suara Total</label>
        <input type="number" name="suara_total" id="suara_total" class="form-control" placeholder="Masukkan Suara Total" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="partisipasi">Partisipasi (%)</label>
        <input type="number" step="0.01" name="partisipasi" id="partisipasi" class="form-control" readonly><br><br>
        </div>

        <button type="submit" class="btn-form primary" onclick="disableButton(this)" >Simpan</button>
        <a href="/data-pemilih" class="btn-form secondary" >Kembali</a>
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

    function disableButton(btn) {
        btn.disabled = true;
        btn.innerText = 'Menyimpan..';
        btn.form.submit();
    }
</script>


@endsection