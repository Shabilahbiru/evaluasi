@extends('layouts.app')

@section('title', 'Data Pemilih')

@section('content')
<div class="welcome-box">
  <h2 style="margin-bottom: 20px;">Data Pemilih</h2>

  @if (session('success'))
  <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
      {{ session('success') }}
  </div>
  @endif

  <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
    <input type="text" id="searchInput" placeholder="Cari Kecamatan/Kelurahan" style="padding: 8px; border: 1px solid #ccc; border-radius: 6px; width: 250px;">
      <a href="{{ route('data-pemilih.create') }}" class="btn-tambah">Tambah Data</a>


      <form action="{{ route('data-pemilih.import') }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 10px;">
          @csrf
          <input type="file" name="file" accept=".xlsx,.xls,.csv" required style="padding: 6px; border: 1px solid #ccc; border-radius: 5px;">
          <button type="submit" class="btn-upload">Import Data</button>
      </form>

      <a href="{{ asset('template/template-data-pemilih.xlsx') }}" class="btn-download">📥</a>
  </div>

  <div class="card" style="overflow-x:auto;">
    <table id="tabel-pemilih" class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kecamatan</th>
                <th>Kelurahan</th>
                <th>DPT Laki-laki</th>
                <th>DPT Perempuan</th>
                <th>DPT Total</th>
                <th>Suara Sah</th>
                <th>Suara Tidak Sah</th>
                <th>Suara Total</th>
                <th>Partisipasi (%)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse ($data_pemilih as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kecamatan }}</td>
                <td>{{ $item->kelurahan }}</td>
                <td>{{ $item->dpt_laki_laki }}</td>
                <td>{{ $item->dpt_perempuan }}</td>
                <td>{{ $item->dpt_total }}</td>
                <td>{{ $item->suara_sah }}</td>
                <td>{{ $item->suara_tidak_sah }}</td>
                <td>{{ $item->suara_total }}</td>
                <td>{{ $item->partisipasi }}</td>
                <td>
                    <a href="{{ route('data-pemilih.edit', $item->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i></a>
                    <form id="delete-form-{{ $item->id }}" action="{{ route('data-pemilih.destroy', $item->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="confirmDelete(event, 'delete-form-{{ $item->id }}')">
                            <i class="fas fa-trash-alt"></i></button>
                    </form>
                    {{-- <a href="{{ route('data-pemilih.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('data-pemilih.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Hapus</button>
                    </form> --}}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center;">Belum ada data pemilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
  </div>
  <br>
  <ul class="pagination pagination-sm justify-content-end">
    @if ($data_pemilih->onFirstPage())
        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
    @else
        <li class="page-item"><a class="page-link" href="{{ $data_pemilih->previousPageUrl() }}">Previous</a></li>
    @endif

    @for ($i = 1; $i <= $data_pemilih->lastPage(); $i++)
        <li class="page-item {{ $data_pemilih->currentPage() == $i ? 'active' : '' }}">
        <a class="page-link" href="{{ $data_pemilih->url($i) }}">{{ $i }}</a></li>
    @endfor

    @if ($data_pemilih->hasMorePages())
        <li class="page-item"><a class="page-link" href="{{ $data_pemilih->nextPageUrl() }}">Next</a></li>
    @else
        <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
    @endif
  </ul>

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tabel-pemilih').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [],
        });
    });
</script>

<script>
    function confirmDelete(event, formId) {
        event.preventDefault();
        Swal.fire({
            title: 'Yakin anda akan menghapus data ini?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endpush
