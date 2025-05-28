@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Kegiatan</h1>

    <!-- Notifikasi sukses -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Notifikasi error -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> Ada masalah pada input:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Tambah Kegiatan -->
    <form action="{{ route('kegiatan.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="tanggal">Tanggal Kegiatan</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="lokasi">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" required>
        </div>

        <div class="form-group mb-4">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

