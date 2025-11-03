<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Evaluasi Partisipasi Bakesbangpol</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo-group">
            <img src="{{ asset('img/Bakesbangpol.png') }}" alt="Logo Bakesbangpol" class="logo">
            <div style="display: flex; flex-direction: column;">
                <h2 class="logo-text">SAPALIH KOTA BANDUNG</h2>
                <small style="margin-top: 2px; font-size: 14px; color: #c6c6c6;">Sistem Evaluasi Partisipasi Pemilih Kota Bandung</small>
            </div>
            </div>
            <ul class="nav-links">
                <li><a href="/home">Home</a></li>
                <li><a href="#tentang">About</a></li>
                <li><a href="/login">Login</a></li>
            </ul>
        </div>
    </nav>

    <div class="hero">
        <div class="overlay">
            <h1>Bakesbangpol Kota Bandung</h1>
            <h2>Meningkatkan Partisipasi Pemilih Dengan Pendidikan Politik</h2>
        </div>
    </div>

    <section class="intro" id="tentang">
        <h2>Selamat Datang di Sistem SAPALIH Kota Bandung</h2>
        <p>Sitem Evaluasi Partisipasi Pemilih Kota Bandung bertujuan untuk meningkatkan transparansi dan efektivitas program pendidikan politik melalui analisis data yang akurat dan berbasis web.</p>
        <a href="/login" class="cta-button">Masuk ke Sistem</a>
    </section>

    <footer>
        <p>&copy; 2025 Bakesbangpol Kota Bandung</p>
    </footer>
</body>
</html>
