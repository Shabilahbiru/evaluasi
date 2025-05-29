<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Sistem Evaluasi Bakesbangpol')</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>  
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  
</head>
<body>
  <!-- HEADER -->
  <header class="top-header" style="width: 100%;">
    <div class="header-container">
      <img src="{{ asset('img/Bakesbangpol.png') }}" alt="Logo Bakesbangpol" class="logo">
      <h2>Bakesbangpol Kota Bandung</h2>
    </div>
    <div style="margin-left:auto; font-weight:bold;">Sistem Evaluasi Bakesbangpol</div>
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
      @if(Auth::user()->foto)
        <img src="{{ asset('foto/' . Auth::user()->foto) }}" alt="Foto Profil" class="profil-foto">
      @else
        <img src="{{ asset('img/user.png') }}" alt="Default Foto" class="profil-foto">
      @endif
        <h3>{{ Auth::user()->username }}</h3>
        <span>{{ ucfirst(Auth::user()->role) }} • Online</span>
      </div>
      <nav class="menu">
        <a href="/dashboard"><i class="fas fa-home"></i> 
          <span class="menu-text">Dashboard</span></a>
        <a href="/data-pemilih"><i class="fas fa-users"></i>
          <span class="menu-text">Data Pemilih</span></a>
        <a href="/clustering"><i class="fas fa-project-diagram"></i> 
          <span class="menu-text">Clustering</span></a>
        <a href="/hasil-evaluasi"><i class="fas fa-chart-line"></i> 
          <span class="menu-text">Hasil Evaluasi</span></a>
        <a href="{{ route('akun.pengaturan') }}"><i class="fas fa-users-cog"></i>
          <span class="menu-text">Setting</span></a>
        <a href="#" onclick="confirmLogout(event)">
          <i class="fas fa-sign-out-alt"></i> 
          <span class="menu-text">Logout</span></a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
      </nav>
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
        const suaraTotal = parseFloat(document.getElementById('suara_total').value) || 0;
        const dptTotal = parseFloat(document.getElementById('dpt_total').value) || 0;
        let partisipasi = 0;

        if (dptTotal > 0) {
            partisipasi = (suaraTotal / dptTotal) * 100;
        }

        document.getElementById('partisipasi').value = partisipasi.toFixed(2);
    }

    document.getElementById('suara_total').addEventListener('input', hitungPartisipasi);
    document.getElementById('dpt_total').addEventListener('input', hitungPartisipasi);

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


</body>
</html>
