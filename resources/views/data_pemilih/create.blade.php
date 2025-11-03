@extends('layouts.app')

@section('title', 'Tambah Data Pemilih')

@section('content')
<div class="container">
    <h3 style="margin-bottom: 20px">Tambah Data Pemilih</h3>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link tab-link active" data-tab="manual-tab" href="#">Input Manual</a>
        </li>
        <li class="nav-item">
            <a class="nav-link tab-link" data-tab="import-tab" href="#">Upload File</a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link tab-link" data-tab="scan-tab" href="#">Upload Scan</a>
        </li> --}}
    </ul>
</div>


<div id="manual-tab" class="tab-content active">
<div class="form-container">
<div class="card">
    
    <h4 style="margin-bottom: 20px">Form Input Data Pemilih</h4>

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

        <div class="form-group">
            <label for="jenis_pemilu">Jenis Pemilu</label>
            <select name="jenis_pemilu" id="jenis_pemilu" class="form-control" required>
                <option value="">--Pilih Jenis Pemilu--</option>
                <option value="Presiden">Presiden</option>
                <option value="DPR">DPR</option>
                <option value="DPD">DPD</option>
                <option value="DPRD Provinsi">DPRD Provinsi</option>
                <option value="DPRD Kabupaten/Kota">DPRD Kabupaten/Kota</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="kecamatan">Kecamatan</label>
        <select name="kecamatan" id="kecamatan" class="form-control" required>
            <option value="">--Pilih Kecamatan--</option>
            <option value="Andir">Andir</option>
            <option value="Antapani">Antapani</option>
            <option value="Arcamanik">Arcamanik</option>
            <option value="Astana Anyar">Astana Anyar</option>
            <option value="Babakan Ciparay">Babakan Ciparay</option>
            <option value="Bandung Kidul">Bandung Kidul</option>
            <option value="Bandung Kulon">Bandung Kulon</option>
            <option value="Bandung Wetan">Bandung Wetan</option>
            <option value="Batununggal">Batununggal</option>
            <option value="Bojongloa Kaler">Bojongloa Kaler</option>
            <option value="Bojongloa Kidul">Bojongloa Kidul</option>
            <option value="Buah Batu">Buah Batu</option>
            <option value="Cibiru">Cibiru</option>
            <option value="Cibeunying Kaler">Cibeunying Kaler</option>
            <option value="Cibeunying Kidul">Cibeunying Kidul</option>
            <option value="Cicendo">Cicendo</option>
            <option value="Cidadap">Cidadap</option>
            <option value="Cinambo">Cinambo</option>
            <option value="Coblong">Coblong</option>
            <option value="Gedebage">Gedebage</option>
            <option value="Kiaracondong">Kiaracondong</option>
            <option value="Lengkong">Lengkong</option>
            <option value="Mandalajati">Mandalajati</option>
            <option value="Panyileukan">Panyileukan</option>
            <option value="Rancasari">Rancasari</option>
            <option value="Regol">Regol</option>
            <option value="Sukajadi">Sukajadi</option>
            <option value="Sukasari">Sukasari</option>
            <option value="Sumur Bandung">Sumur Bandung</option>
            <option value="Ujungberung">Ujungberung</option>
        </select><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="dpt_laki_laki">DPT Laki-laki</label>
        <input type="number" name="dpt_laki_laki" id="dpt_laki_laki" min="0" class="form-control" placeholder="Masukkan DPT Laki-laki" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="dpt_perempuan">DPT Perempuan</label>
        <input type="number" name="dpt_perempuan" id="dpt_perempuan" min="0" class="form-control" placeholder="Masukkan DPT Perempuan" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="dpt_total">DPT Total</label>
        <input type="number" name="dpt_total" id="dpt_total" min="0" class="form-control" placeholder="Masukkan DPT Total" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="suara_sah">Suara Sah</label>
        <input type="number" name="suara_sah" id="suara_sah" min="0" class="form-control" placeholder="Masukkan Suara Sah" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="suara_tidak_sah">Suara Tidak Sah</label>
        <input type="number" name="suara_tidak_sah" id="suara_tidak_sah" min="0" class="form-control" placeholder="Masukkan Suara Tidak Sah" required><br>
        </div>

        <div class="form-group" style="margin-bottom: 2px;">
        <label for="suara_total">Suara Total</label>
        <input type="number" name="suara_total" id="suara_total" min="0" class="form-control" placeholder="Masukkan Suara Total" required><br>
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
</div>

<div id="import-tab" class="tab-content">
    <div class="form-container" style="width: 500px;">
    <div class="card">
    <h4 style="margin-bottom: 20px;">Upload File Data Pemilih</h4>

    @if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('data-pemilih.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="jenis_pemilu">Pilih Jenis Pemilu</label>
            <select name="jenis_pemilu" id="jenis_pemilu" class="form-control" required>
                <option label="" value="">--Pilih Jenis Pemilu--</option>
                <option value="Presiden">Presiden</option>
                <option value="DPR">DPR</option>
                <option value="DPD">DPD</option>
                <option value="DPRD Provinsi">DPRD Provinsi</option>
                <option value="DPRD Kabupaten/Kota">DPRD Kabupaten/Kota</option>
            </select>
        </div>
        <div class="form-group">
            <label for="file" style="font-weight: 600;">Pilih File</label>
            <input type="file" name="file" id="file" class="form-control" 
            style="padding: 4px 8px; border: 1px solid #ccc; border-radius: 6px; width:100%;" required>
        </div>
        <button type="submit" class="btn-form primary">Upload</button>
        <a href="{{ asset('template/template-data-pemilih.xlsx') }}" class="btn-download">📥 Unduh Template</a>
    </form>
    </div>
    </div>
</div>
    

{{-- <div id="scan-tab" class="tab-content">
    <div class="form-container" style="width: 500px;">
        <div class="card mb-4">
            <h4 style="margin-bottom: 20px;">Upload Scan Data Pemilih (DPT)</h4>
            <form action="{{ route('upload.dpt') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mt-2">
                    <label for="jenis_pemilu">Pilih Jenis Pemilu</label>
                    <select name="jenis_pemilu" id="jenis_pemilu" class="form-control" required>
                        <option value="">--Pilih Jenis Pemilu</option>
                        <option value="Presiden">Presiden</option>
                        <option value="DPR">DPR</option>
                        <option value="DPD">DPD</option>
                        <option value="DPRD Provinsi">DPRD Provinsi</option>
                        <option value="DPRD Kabupaten/Kota">DPRD Kabupaten/Kota</option>
                    </select>
                </div>

                <div class="form-group mt-2">
                    <label for="image">Gambar Scan DPT</label>
                    <input type="file" name="image" id="image" accept="image/*" class="form-control" required>
                </div>

                
                <button type="submit" class="btn btn-primary mt-3">Upload DPT</button>
            </form>
        </div>

        <div class="card mb-4">
            <h4>Upload Scan Data Suara</h4>
            <form action="{{ route('upload.suara') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mt-2">
                <label for="image">Gambar Scan Data Suara</label>
                <input type="file" name="image" id="image" accept="image/*" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success mt-3">Upload & Proses</button>
            </form>
        </div>

    </div>
</div> --}}

<script>
    document.querySelectorAll('.tab-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.tab-link').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });


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

<style>
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}

/* Hapus spinner panah pada input number */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
}
</style>
