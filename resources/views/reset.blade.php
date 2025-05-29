<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reset Password - Sistem Evaluasi Bakesbangpol</title>
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body class="login-body">
  <div class="login-wrapper">
    <div class="login-box">
      <div class="login-header">
        <img src="{{ asset('img/Bakesbangpol.png') }}" alt="Logo Bakesbangpol" class="logo1">
        <h1>Reset Password</h1>
        <p>Masukkan password baru untuk akun anda</p> 
      </div>

      @if ($errors->any())
      <div class="alert-error">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>

      @endif
      <form action="{{ route('reset.action') }}" method="POST" class="login-form">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label for="password">Password Baru</label>
        <input type="password" name="password" id="password" placeholder="Password baru" required>

        <label for="password_confirmation">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi password" required>

        <div class="login-actions">
          <button type="submit" class="btn-login">Reset Password</button>
          <a href="/login" class="btn-register">Kembali</a>
        </div>
      </form>
    </div>
  </div>
  
</body>
</html>

