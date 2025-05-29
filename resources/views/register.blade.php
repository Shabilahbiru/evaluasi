<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Evaluasi Partisipasi Bakesbangpol</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <input type="password" name="password" placeholder="Password" required>
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>

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
</body>
</html>