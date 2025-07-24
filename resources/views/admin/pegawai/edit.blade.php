@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Pegawai</h1>

    <form method="POST" action="{{ route('pegawai.update', $pegawai->id) }}" enctype="multipart/form-data">
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

        <div class="form-group">
            <label>Divisi</label>
            <select name="divisi" class="form-control" required>
                <option value="">-- Pilih Divisi --</option>
                <option value="Divisi P3H" {{ $pegawai->divisi == 'Divisi P3H' ? 'selected' : '' }}>Divisi P3H</option>
                <option value="Divisi Pelayanan Hukum" {{ $pegawai->divisi == 'Divisi Pelayanan Hukum' ? 'selected' : '' }}>Divisi Pelayanan Hukum</option>
                <option value="Bagian TU dan Umum" {{ $pegawai->divisi == 'Bagian TU dan Umum' ? 'selected' : '' }}>Bagian TU dan Umum</option>
            </select>
            @error('divisi')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Foto Pegawai</label><br>
            @if($pegawai->foto)
                <img src="{{ asset('storage/foto_pegawai/' . $pegawai->foto) }}" alt="Foto Pegawai" width="120" class="mb-2"><br>
            @endif
            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
            @error('foto')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-2">Update</button>
        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary mt-2">Kembali ke Daftar Pegawai</a>
    </form>
</div>
@endsection