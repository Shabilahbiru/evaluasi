@extends('layouts.app')

@section('title', 'Kelola Role Pengguna')

@section('content')
<div class="card shadow-sm border">
    <h2 style="margin-bottom: 23px; text-align: left;">Kelola Role Pengguna</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
    <table class="table table-bordered" style="font-size: 1rem;">
        <thead>
            <tr>
                <th style="font-weight: 700;">Nama</th>
                <th style="font-weight: 700;">Email</th>
                <th style="font-weight: 700;">Role Pengguna</th>
                <th style="font-weight: 700;">Ubah Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('users.update-role') }}" method="POST">
                    @csrf
                    <td style="font-weight: normal;">{{ $user->name }}</td>
                    <td style="font-weight: normal;">{{ $user->email }}</td>
                    <td style="font-weight: normal;">
                        @if($user->role === 'admin')
                        <span style="display: inline-flex; align-items: center;">
                            <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #3b82f6; margin-right: 6px;"></span>
                            Admin
                        </span>
                        @elseif($user->role === 'reviewer')
                        <span style="display: inline-flex; align-items: center;">
                            <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #f59e0b; margin-right: 6px;"></span>
                            Reviewer
                        </span>
                        @endif
                    </td>
                    <td>
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <select name="role" class="form-control d-inline w-auto">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : ''}}>Admin</option>
                            <option value="reviewer" {{ $user->role == 'reviewer' ? 'selected' : '' }}>Reviewer</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </td>
                </form>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection