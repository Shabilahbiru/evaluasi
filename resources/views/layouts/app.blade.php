<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Sistem Evaluasi Bakesbangpol')</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" /> 
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
  
</head>
<body>
  <!-- HEADER -->
<header class="top-header" style="width: 100%; padding: 10px 0;">
  <div style="display: flex; align-items: center; justify-content: center;">
    <img src="{{ asset('img/Bakesbangpol.png') }}" alt="Logo Bakesbangpol" style="height: 60px; margin-right: 15px; margin-left: 40px;">
    <div style="display: flex; flex-direction: column;">
      <h2 style="margin: 0; font-size: 24px; font-weight: bold; color: #B00020;">SAPALIH KOTA BANDUNG</h2>
      <small style="margin-top: 2px; font-size: 14px; color: #444;">Sistem Evaluasi Partisipasi Pemilih Kota Bandung</small>
    </div>
  </div>

  <div style="position: absolute; top: 15px; right: 30px;">
  <form method="GET" action="{{ route('dashboard') }}" style="display: flex; align-items: center;">
    <label for="jenis_pemilu" class="mr-2 text-dark font-weight-bold" style="margin-right: 8px;">Jenis Pemilu:</label>
    <select name="jenis_pemilu" id="jenis_pemilu" class="form-control" style="width: auto;" onchange="this.form.submit()">
        @foreach($jenisPemiluList ?? [] as $jp)
            <option value="{{ $jp }}" {{ session('jenis_pemilu') === $jp ? 'selected' : '' }}>{{ $jp }}</option>
        @endforeach
    </select>
  </form>
</div>

</header>


  <!-- MAIN LAYOUT -->
  <div class="main-container">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar" style="max-width: 40%">

      <div class="sidebar-header">
        <span class="sidebar-toggle" id="toggleSidebar" style="margin-bottom: 22px;">
          <i class="fas fa-bars"></i>
        </span>
      </div>

      <div class="profile" style="margin-bottom: 8px;">
      @if(Auth::check() && Auth::user()->foto)
        <img src="{{ asset('foto/' . Auth::user()->foto) }}" alt="Foto Profil" class="profil-foto">
      @else
        <img src="{{ asset('img/user.png') }}" alt="Default Foto" class="profil-foto">
      @endif
      @if(Auth::check())
        <h3>{{ Auth::user()->username }}</h3>
        <span>{{ ucfirst(Auth::user()->role) }}</span>
      </div>
      <nav class="menu">
          <a href="/dashboard"><i class="fas fa-home"></i> 
          <span class="menu-text">Dashboard</span></a>

          <a href="{{ route('profil-bakesbangpol') }}"><i class="fas fa-building"></i>
          <span class="menu-text">Profil Bakesbangpol</span></a>

        @if(in_array(auth()->user()->role, ['admin', 'admin_master']))  
          <a href="/data-pemilih"><i class="fas fa-users"></i>
          <span class="menu-text">Data Pemilih</span></a>
        @elseif(auth()->user()->role === 'reviewer')
          <a href="/data-pemilih"><i class="fas fa-users"></i>
          <span class="menu-text">Data Pemilih</span></a>
        @endif

        @if(in_array(auth()->user()->role, ['admin', 'admin_master']))
          <a href="/clustering"><i class="fas fa-project-diagram"></i> 
          <span class="menu-text">Clustering</span></a>
        @endif

        @if (Auth::user()->role === 'reviewer')
          <a href="/hasil-evaluasi"><i class="fas fa-chart-line"></i> 
          <span class="menu-text">Hasil Evaluasi</span></a>
        @endif

        @if(auth()->user()->role === 'admin_master')
          <a href="{{ route('users.ubah-role') }}"><i class="fas fa-user-shield"></i>
          <span class="menu-text">Role</span></a>
        @endif

        <a href="{{ route('akun.pengaturan') }}"><i class="fas fa-users-cog"></i>
          <span class="menu-text">Edit Profile</span></a>

        <a href="#" onclick="confirmLogout(event)">
          <i class="fas fa-sign-out-alt"></i> 
          <span class="menu-text">Logout</span></a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
      </nav>
      @endif
    </aside>

    <!-- CONTENT -->

    <main class="dashboard-content" style="width: 60%;">
      @yield('content')
    </main>
  </div>

  <script>
    function confirmLogout(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Yakin logout?',
            text: 'Kamu akan keluar dari sistem.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
  </script>

{{-- INI SIDEBAR TOGGLE --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar'); 

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    }
});
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
      const tableBody = document.getElementById('tableBody');

      if (searchInput && tableBody){
        searchInput.addEventListener('keyup', function() {
        const keyword = searchInput.value.toLowerCase();
        const rows = tableBody.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
          const row = rows[i];
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(keyword) ? '' : 'none';

      }
      });
      }
    });
</script>

@section('scripts')
<script>
    function hitungPartisipasi() {
        const suaraTotalEl = document.getElementById('suara_total');
        const dptTotalEl = document.getElementById('dpt_total');
        const partisipasiEl = document.getElementById('partisipasi');
        
        if (!suaraTotalEl || !dptTotalEl || !partisipasiEl) {
          return; // jika elemen tidak ada, keluar dari fungsi
        }

        const suaraTotal = parseFloat(suaraTotalEl.value) || 0;
        const dptTotal = parseFloat(dptTotalEl.value) || 0;
        let partisipasi = 0;

        if (dptTotal > 0) {
            partisipasi = (suaraTotal / dptTotal) * 100;
        }

        partisipasiEl.value = partisipasi.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
      const suaraTotalEl = document.getElementById('suara_total');
      const dptTotalEl = document.getElementById('dpt_total');

      if (suaraTotalEl && dptTotalEl) {
        suaraTotalEl.addEventListener('input', hitungPartisipasi);
        dptTotalEl.addEventListener('input', hitungPartisipasi);
      }
    });

</script>

@stack('scripts')
@yield('scripts')

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<script>
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
</script>

</body>
</html>
