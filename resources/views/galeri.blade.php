<div class="row">
    @for ($i = 1; $i <= 6; $i++)
    <div class="col-md-4 mb-3">
        <img src="{{ asset('img/dokumentasi/kegiatan' . $i . '.jpg') }}" class="img-fluid rounded shadow-sm">
    </div>
    @endfor
</div>
