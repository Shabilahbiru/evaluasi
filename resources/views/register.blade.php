<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Evaluasi Partisipasi Bakesbangpol</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="login-body">
    <div class="login-wrapper">
        <div class="login-box">
            <div class="login-header">
                <img src="{{ asset('img/Bakesbangpol.png') }}" alt="Logo Bakesbangpol" class="logo1">
                <h1>Register</h1>
                <p>Buat Akun Baru</p>
            </div>

            @if ($errors->any())
                <div style="color: red;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('register.action') }}" method="POST" class="login-form">
                @csrf
                <label>Nama Lengkap</label>
                <input type="text" id="name" name="name" placeholder="Nama Lengkap" required>
                <label>Username</label>
                <input type="text" name="username" placeholder="Username" required>
                <label>Email</label>
                <input type="email" name="email" placeholder="Email" required>
                <label>Password</label>
                <div class="password-container" style="position: relative; display: flex; align-items: center;">
                <input type="password" id="password" name="password" placeholder="Masukkan Password" required style="flex: 1; padding-right: 40px; height: 40px;">
                <i id="togglePassword" class="fa-solid fa-eye-slash" style="position: absolute; top: 13px; right: 10px; font-size: 14px; color: #555; cursor: pointer;"></i>
                </div>
                <label>Konfirmasi Password</label>
                <div class="password-container" style="position: relative; display: flex; align-items: center;">
                <input type="password" id="password" name="password_confirmation" placeholder="Konfirmasi Password" required style="flex: 1; padding-right: 40px; height: 40px;">
                <i id="togglePassword" class="fa-solid fa-eye-slash" style="position: absolute; top: 13px; right: 10px; font-size: 14px; color: #555; cursor: pointer;"></i>
                </div>

                <div class="login-actions">
                    <button type="submit" class="btn-login">Register</button>
                    <a href="/login" class="btn-register">Login</a>
                </div>
            </form>
        </div>
    </div>  
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

@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ $errors->first() }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

<script>
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');

  togglePassword.addEventListener('click', function () {
    const isPassword = passwordInput.type === 'password';
    passwordInput.type = isPassword ? 'text' : 'password';

    // Ganti ikon antara mata biasa dan mata dicoret
    this.classList.toggle('fa-eye-slash'); // coret
    this.classList.toggle('fa-eye');       // mata biasa
  });
</script>

</body>
</html>