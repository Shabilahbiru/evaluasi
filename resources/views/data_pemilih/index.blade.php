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

  @php
    
    $showAksi = Auth::user()->role === 'admin' || Auth::user()->role === 'admin_master';
    $jumlahKolom = $showAksi ? 10 : 9;   
  @endphp

  <div style="margin-bottom: 20px; display: flex; align-items:center; flex-wrap: wrap; gap:10px;">
  <div>
    @if($showAksi)
        <a href="{{ route('data-pemilih.create') }}" class="btn-tambah">Tambah Data</a>
    @endif
  </div>
</div>

  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
    <div class="show-entries">
        <label for="entriesSelect">show</label>
    <select id="entriesSelect" onchange="changeEntries()" style="padding: 6px; border: 1px solid #ccc; border-radius: 5px;">
        <option value="10" {{ request('per_page') == 10 ? 'selected' : ''}}>10</option>
        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
    </select>
    <span>entries</span>
    </div>
    <div style="margin-right: 6px;">
    <input type="text" id="searchInput" placeholder="Cari Kecamatan" style="padding: 8px; border: 1px solid #ccc; border-radius: 6px; width: 250px;">
    </div>
</div>

  <div class="card" style="overflow-x:auto;">
    @if(isset($jenisPemilu))
    <div class="alert alert-info text-center mt-0" style="font-size: 16px;">
       Data pemilih berdasarkan <strong>Jenis Pemilu: {{ $jenisPemilu }}</strong>
    </div>
    @endif
    <table id="tabel-pemilih" class="table">
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
                @if($showAksi)
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse ($data_pemilih as $item)
            <tr>
                <td style="font-weight: normal;">{{ $data_pemilih->firstItem() + $loop->index }}</td>
                <td style="font-weight: normal;">{{ $item->kecamatan }}</td>
                <td style="font-weight: normal;">{{ number_format($item->dpt_laki_laki) }}</td>
                <td style="font-weight: normal;">{{ number_format($item->dpt_perempuan) }}</td>
                <td style="font-weight: normal;">{{ number_format($item->dpt_total) }}</td>
                <td style="font-weight: normal;">{{ number_format($item->suara_sah) }}</td>
                <td style="font-weight: normal;">{{ number_format($item->suara_tidak_sah) }}</td>
                <td style="font-weight: normal;">{{ number_format($item->suara_total) }}</td>
                <td style="font-weight: normal;">{{ $item->partisipasi }}</td>
                @if($showAksi)                
                <td>
                    <a href="{{ route('data-pemilih.edit', $item->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i></a>
                    <form id="delete-form-{{ $item->id }}" action="{{ route('data-pemilih.destroy', $item->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="confirmDelete(event, 'delete-form-{{ $item->id }}')">
                            <i class="fas fa-trash-alt"></i></button>
                    </form>                    
                </td>
                @endif    
            </tr>
            @empty
            <tr>
                @for ($i = 0; $i < $jumlahKolom; $i++)
                @if ($i == floor($jumlahKolom / 2))
                <td colspan="1" style="text-align: center;" id="emptyMessage" class="text-center" colspan="1">Belum ada data pemilih.</td>
                @else
                <td></td>
            @endif
            @endfor
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
        <li class="page-item"><a class="page-link" href="{{ $data_pemilih->appends(request()->query())->previousPageUrl() }}">Previous</a></li>
    @endif

    @for ($i = 1; $i <= $data_pemilih->lastPage(); $i++)
        <li class="page-item {{ $data_pemilih->currentPage() == $i ? 'active' : '' }}">
        <a class="page-link" href="{{ $data_pemilih->appends(request()->query())->url($i) }}">{{ $i }}</a></li>
    @endfor

    @if ($data_pemilih->hasMorePages())
        <li class="page-item"><a class="page-link" href="{{ $data_pemilih->appends(request()->query())->nextPageUrl() }}">Next</a></li>
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
        if ($.fn.DataTable.isDataTable('#tabel-pemilih')) {
            $('#tabel-pemilih').DataTable().destroy();
        }

        $('#tabel-pemilih').DataTable({
            paging: false,
            info: false,
            searching: false,
            ordering: true,
        });
    });
</script>

<script>
    function changeEntries() {
        let perPage = document.getElementById('entriesSelect').value;
        let params = new URLSearchParams(window.location.search);
        if (perPage == 'all') {
            window.location.href = "{{ route('data-pemilih.index') }}" + '?per_page=' + 99999;
        } else {
            params.set('per_page', perPage);
            window.location.search = params.toString();
        }
    }
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
