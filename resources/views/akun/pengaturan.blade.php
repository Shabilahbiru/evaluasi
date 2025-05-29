@extends('layouts.app')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('title', 'Pengaturan Akun')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pengaturan.css') }}">
<div class="container-pengaturan">
    <h3>Pengaturan Akun</h3>

    @if(session('success'))
    <script>
        Swal.fire('Berhasil!','{{ session('success') }}', 'success');
    </script>
    @endif

    <form action="{{ route('akun.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="name">Nama</label>
    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>

    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>

    <label for="password">Password Baru (kosongkan jika tidak diganti)</label>
    <input type="password" name="password" id="password" placeholder="Password Baru"> 
    <input type="password" name="password_confirmation" placeholder="Konfirmasi Pasword">

    <label for="foto">Foto Profil (Opsional)</label>
    @if($user->foto)
        <img src="{{ asset('foto/' . $user->foto) }}" alt="Foto Profil" style="max-width: 150px; margin-bottom: 10px;">
    @endif
        <input type="file" name="foto">

        <br>

    @if(Auth::user()->foto)
        <div class="mb-2">
            <input type="checkbox" name="hapus_foto" id="hapus_foto" value="1">
            <label for="hapus_foto">Hapus foto profil dan kembali ke default</label>
        </div>
    @endif


    <button type="submit">Simpan Perubahan</button>
    </form>

    @if ($errors->any())
    <ul style="color: red">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
        
</div>
@endsection
