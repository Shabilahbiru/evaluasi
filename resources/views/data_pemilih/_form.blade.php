<div class="form-group" style="margin-bottom: 2px">
    <label for="kecamatan" style="display: block; margin-bottom: 5px;">Kecamatan</label>
    <input type="text" name="kecamatan" id="kecamatan" placeholder="Masukkan Kecamatan" class="form-control" value="{{ old('kecamatan', $data->kecamatan) }}" required><br>
</div>

<div class="form-group" style="margin-bottom: 2px">
    <label for="kelurahan" style="display: block; margin-bottom: 5px;">Kelurahan</label>
    <input type="text" name="kelurahan" id="kelurahan" placeholder="Masukkan Kelurahan" class="form-control" value="{{ old('kelurahan', $data->kelurahan) }}" required><br>
</div>

<div class="form-group" style="margin-bottom: 2px">
    <label for="dpt_laki_laki">DPT Laki-laki</label>
    <input type="number" name="dpt_laki_laki" id="dpt_laki_laki" placeholder="Masukkan DPT Laki-laki" class="form-control" value="{{ old('dpt_laki_laki', $data->dpt_laki_laki) }}" required><br>
</div>

<div class="form-group" style="margin-bottom: 2px">
    <label for="dpt_perempuan">DPT Perempuan</label>
    <input type="number" name="dpt_perempuan" id="dpt_perempuan" placeholder="Masukkan DPT Perempuan" class="form-control" value="{{ old('dpt_perempuan', $data->dpt_perempuan) }}" required><br>
</div>

<div class="form-group" style="margin-bottom: 2px">
    <label for="dpt_total">DPT Total</label>
    <input type="number" name="dpt_total" id="dpt_total" placeholder="Masukkan DPT Total" class="form-control" value="{{ old('dpt_total', $data->dpt_total) }}" required><br>
</div>

<div class="form-group" style="margin-bottom: 2px">
    <label for="suara_sah">Suara Sah</label>
    <input type="number" name="suara_sah" id="suara_sah" placeholder="Masukkan Suara Sah" class="form-control" value="{{ old('suara_sah', $data->suara_sah) }}" required><br>
</div>

<div class="form-group" style="margin-bottom: 2px">
    <label for="suara_tidak_sah">Suara Tidak Sah</label>
    <input type="number" name="suara_tidak_sah" id="suara_tidak_sah" placeholder="Masukkan Suara Tidak Sah" class="form-control" value="{{ old('suara_tidak_sah', $data->suara_tidak_sah) }}" required><br>
</div>

<div class="form-group" style="margin-bottom: 2px">
    <label for="suara_total">Suara Total</label>
    <input type="number" name="suara_total" id="suara_total" placeholder="Masukkan Suara Total" class="form-control" value="{{ old('suara_total', $data->suara_total) }}" required><br>
</div>

<div class="form-group" style="margin-bottom: 2px">
    <label for="partisipasi">Partisipasi (%)</label>
    <input type="number" step="0.01" name="partisipasi" id="partisipasi" class="form-control" value="{{ old('partisipasi', $data->partisipasi) }}" readonly><br><br>
</div>