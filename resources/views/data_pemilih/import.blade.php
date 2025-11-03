@extends('layouts.app')

@section('content')
<div class="form-container">
    <h2>Import Data Pemilih</h2>

    @if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif
    
    <form action="{{ route('data-pemilih.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit" class="btn-import">Upload</button>
    </form>
</div>
@endsection
