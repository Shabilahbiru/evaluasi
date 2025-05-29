<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lupa Password - Sistem Evaluasi Bakesbangpol</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body class="login-body">
    <div class="login-wrapper">
        <div class="login-box">
            <div class="login-header">
                <img src="{{ asset('img/Bakesbangpol.png') }}" alt="Logo Bakesbangpol" class="logo1">
                <h1>Lupa Password</h1>
                <p>Masukkan email untuk mengatur ulang password anda</p>
            </div>

            @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('forgot.action') }}" method="POST" class="login-form">
                @csrf
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Masukkan Email" required>

                <div class="login-actions">
                    <button type="submit" class="btn-login">Kirim Link Reset</button>
                    <a href="/login" class="btn-register">Kembali</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>