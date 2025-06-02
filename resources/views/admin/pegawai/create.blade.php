@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Pegawai</h1>

    <form method="POST" action="{{ route('pegawai.store') }}">
        @csrf
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label>NIP</label>
            <input type="text" name="nip" class="form-control" required>
            @error('nip')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Divisi</label>
            <select name="divisi" class="form-control" required>
                <option value="">-- Pilih Divisi --</option>
                <option value="Divisi P3H">Divisi P3H</option>
                <option value="Divisi Pelayanan Hukum">Divisi Pelayanan Hukum</option>
                <option value="Bagian TU dan Umum">Bagian TU dan Umum</option>
            </select>
            @error('divisi')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>

    <hr>

    <h2>Upload Pegawai via Excel</h2>
    <form action="{{ route('pegawai.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="file_excel">Pilih file Excel:</label>
        <input type="file" name="file_excel" class="form-control-file" accept=".xlsx,.xls" required>
        @error('file_excel')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <button type="submit" class="btn btn-success mt-2">Upload & Generate QR</button>
</form>

@if ($errors->any())
    <div class="mt-3 alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</div>
<div class="mt-4">
        <a href="{{ route('pegawai.index') }}" class="btn btn-info">Kembali ke Daftar Pegawai</a>
    </div>
</div>
@endsection
<a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

