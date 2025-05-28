@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Pegawai</h1>

    <form method="POST" action="{{ route('pegawai.update', $pegawai->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $pegawai->nama }}" required>
            @error('nama')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>NIP</label>
            <input type="text" name="nip" class="form-control" value="{{ $pegawai->nip }}" required>
            @error('nip')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-2">Update</button>
        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary mt-2">Kembali ke Daftar Pegawai</a>
    </form>
</div>
@endsection
