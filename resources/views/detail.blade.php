@extends('layouts.app')

@section('title', $title)

@section('content')
<style>
    .section-header {
        background: linear-gradient(to right, #4f8ae2, #7ea3f8);
        color: white;
        padding: 60px 0;
        text-align: center;
    }

    .section-header h1 {
        font-weight: bold;
        font-size: 36px;
    }

    .section-body {
        padding: 40px 20px;
        max-width: 900px;
        margin: auto;
    }

    .section-body p, ul {
        font-size: 17px;
        line-height: 1.8;
        color: #333;
        text-align: justify;
    }

    .section-body strong {
        color: #000;
    }

    .section-body img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
    }
</style>

<div class="section-header">
    <h1>{{ $title }}</h1>
</div>

<div class="section-body">
    @switch(Str::slug($title, '-'))
        @case('tentang-kami')
            <p>
                <strong>Badan Kesatuan Bangsa dan Politik (Bakesbangpol) Kota Bandung</strong> adalah perangkat daerah yang memiliki peran penting dalam menjaga stabilitas politik daerah dan memperkuat wawasan kebangsaan masyarakat. Melalui pendekatan edukatif, koordinatif, dan strategis, Bakesbangpol menjadi garda terdepan dalam pembinaan ideologi bangsa serta menjamin terselenggaranya kehidupan berpolitik yang sehat dan demokratis.
            </p>
            <p>
                Bakesbangpol berfungsi sebagai unsur pelaksana urusan pemerintahan di bidang politik dan kesatuan bangsa, yang berada di bawah dan bertanggung jawab kepada Wali Kota melalui Sekretaris Daerah, sebagaimana tercantum dalam <strong>Peraturan Wali Kota Bandung Nomor 73 Tahun 2022</strong>.
            </p>
            <p>
                Dengan semangat partisipatif dan inklusif, Bakesbangpol senantiasa mendukung pembentukan tatanan kehidupan bermasyarakat yang harmonis melalui koordinasi dengan partai politik, organisasi masyarakat, serta berbagai elemen strategis lainnya.
            </p>
            <p>
                Untuk menjalankan tugas tersebut, Bakesbangpol didukung oleh struktur organisasi yang profesional dan sistem kerja yang terintegrasi dalam kerangka pemerintahan Kota Bandung.
            </p>
        @break

        @case('tugas-fungsi')
            <ul>
                <li>Merumuskan dan melaksanakan kebijakan bidang kesatuan bangsa dan politik.</li>
                <li>Melakukan pendidikan politik dan peningkatan wawasan kebangsaan.</li>
                <li>Memfalisitasi pembinaan ormas dan partai politik.</li>
                <li>Melaksanakan koordinasi intelijen daerah dan deteksi dini potensi konflik.</li>
            </ul>
        @break

        @case('kegiatan')
        <p>Bakesbangpol melaksanakan berbagai kegiatan strategis seperti:</p>
        <ol>
            <li>Pendidikan politik untuk meningkatkan partisipasi perempuan politik di Kota Bandung</li>
            <li>Sosialisasi Pemilu dan Pemilihan serentak 2024</li>
            <li>Pembinaan politik bagi organisasi masyarakat</li>
            <li>Pendidikan politik bagi kader penggerak kebangsaan peduli Pemilu/Pemilihan Umum</li>
            <li>Pendidikan demokrasi bagi masyarakat untuk sukses Pemilu dan pemilihan serentak 2024</li>
            <li>Pendidikan demokrasi bagi generasi muda dan komunitas se-Kota Bandung untuk sukses Pemilu dan pemilihan serentak 2024</li>
            <li>Kemah partai politik</li>
            <li>Diskusi politik Kota Bandung</li>
            <li>Bimbingan teknis tentang pertanggungjawaban laporan bantuan keuangan partai politik berasal dari partai politik se-Kota Bandung</li>
            <li>Pendidikan politik bagi perempuan</li>
        </ol>
        @break

        @case('galeri-dokumentasi')
        <style>
            .gallery-img {
            height: 200px;
            object-fit: cover;
            border-radius: 6px;
            transition: 0.3s ease-in-out;
            }

            .gallery-img:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            }

            .gallery-caption {
            font-size: 14px;
            text-align: center;
            margin-top: 6px;
            color: #555;
            }
        </style>

        <p class="mb-4">Berikut adalah dokumentasi dari berbagai kegiatan Bakesbangpol:</p>
        <div class="row">
            @php
            $galeri = [
                ['img' => 'kegiatan1.jpg', 'caption' => 'Pendidikan Politik Untuk Meningkatkan Partisipasi Perempuan Politik Di Kota Bandung'],
                ['img' => 'kegiatan2.jpg', 'caption' => 'Pendidikan Politik Bagi Perempuan'],
                ['img' => 'kegiatan3.jpg', 'caption' => 'Kemah Partai Politik'],
                ['img' => 'kegiatan4.jpg', 'caption' => 'Sosialisasi Pemilu Serentak 2024 Kepada Para Camat, Lurah, SKPD Dan Pimpinan Partai Politik'],
                ['img' => 'kegiatan5.jpg', 'caption' => 'Pembinaan Politik Bagi Organisasi Kemasyarakatan'],
                ['img' => 'kegiatan6.jpg', 'caption' => 'Pendidikan Politik Bagi Kader Penggerak Kebangsaan Peduli PEMILU/Pemilihan Umum ']
            ];
            @endphp

        @foreach ($galeri as $item)
        <div class="col-md-4 mb-4">
            <img src="{{ asset('img/dokumentasi/' . $item['img']) }}" class="img-fluid gallery-img" alt="{{ $item['caption'] }}">
            <div class="gallery-caption">{{ $item['caption'] }}</div>
        </div>
        @endforeach
        </div>
        @break

        @case('kontak')
            <p>
                <strong>Alamat:</strong> Jl. Wastukencana, Babakan Ciamis, Kec. Sumur Bandung, Kota Bandung, Jawa Barat 40117<br>
                <strong>Email:</strong> bkbpl@bandung.go.id<br>
                <strong>Instagram:</strong> bakesbangpol kota bandung<br>
                <strong>Youtube:</strong> bakesbangpol kota bandung<br>
                <strong>Jam Operasional:</strong> Senin-Jumat, pukul 08.00-16.00 WIB
            </p>
            <div class="mt-3">
                <iframe src="https://maps.app.goo.gl/8bNt3x7DC1asWM8P9" width="100%" height="300" style="border:0;" allowfullscreen loading="lazy"></iframe>
            </div>
        @break

        @default
            {!! $content !!}
        @endswitch
</div>
@endsection

