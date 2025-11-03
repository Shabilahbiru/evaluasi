@extends('layouts.app')

@section('title', 'Profil Bakesbangpol')

@section('content')
<style>
    html {
        scroll-behavior: smooth;
    }

    .hero-banner {
        background: url('{{ asset('img/banner-bakesbangpol.jpg') }}') no-repeat center center;
        background-size: cover;
        height: 320px;
        width: 100%;
    }

    .hero-banner h1 {
        font-weight: bold;
        font-size: 36px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .card-profile {
        transition: all 0.3s ease-in-out;
        border: none;
    }

    .card-profile:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .card-profile img {
        object-fit: cover;
        height: 180px;
        width: 100%;
    }

    .card-profile .card-body {
        min-height: 120px;
    }
</style>

<!-- Hero Image Only -->
<div class="hero-banner"></div>

<!-- Overlaying Title in Container Putih -->
<div class="container">
    <div class="card shadow-sm p-4 text-center mb-5" style="margin-top: -80px; background: white; position: relative; z-index: 10; border-top: 5px solid #4f8ae2;">
        <h1 class="font-weight-bold mb-2">Profil Bakesbangpol</h1>
        <p class="lead mb-0">Membangun partisipasi masyarakat dan menjaga stabilitas politik daerah secara inklusif dan strategis.</p>
    </div>
</div>

<div class="container my-5">
    <h2 class="text-center section-title">SEKILAS INFORMASI</h2>

    <div class="row">
        @php
            $items = [
                ['title' => 'Tentang Kami', 'desc' => 'Profil umum dan peran strategis Bakesbangpol Kota Bandung.', 'img' => 'tentang.jpg'],
                ['title' => 'Tugas & Fungsi', 'desc' => 'Tugas pokok, fungsi, dan mandat dalam bidang kesbangpol.', 'img' => 'tupoksi.jpg'],
                ['title' => 'Struktur Organisasi', 'desc' => 'Bagan struktur resmi Bakesbangpol sesuai Perwal No. 73.', 'img' => 'struktur.webp'],
                ['title' => 'Kegiatan', 'desc' => 'Pendidikan politik, kemah partai, diskusi & pembinaan ormas.', 'img' => 'kegiatan.jpg'],
                ['title' => 'Galeri Dokumentasi', 'desc' => 'Dokumentasi visual dari kegiatan Bakesbangpol.', 'img' => 'galeri.jpg'],
                ['title' => 'Kontak', 'desc' => 'Informasi alamat, email, dan kontak resmi Bakesbangpol.', 'img' => 'kontak.jpg'],
            ];
        @endphp

        @foreach ($items as $item)
        <div class="col-md-4 mb-4">
            <div class="card card-profile h-100 position-relative" style="border-bottom: 5px solid #4f8ae2;">
                <a href="{{ route('profil.bakesbangpol.section', ['slug' => Str::slug($item['title'], '-')]) }}" class="stretched-link"></a>
                <img src="{{ asset('img/dokumentasi/' . $item['img']) }}" class="card-img-top" alt="{{ $item['title'] }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $item['title'] }}</h5>
                    <p class="card-text">{{ $item['desc'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
