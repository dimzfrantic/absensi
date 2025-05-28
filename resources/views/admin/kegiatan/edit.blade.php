@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Kegiatan</h1>

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

    <!-- Form Edit Kegiatan -->
    <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="tanggal">Tanggal Kegiatan</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $kegiatan->tanggal->format('Y-m-d')) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="lokasi">Lokasi</label>
            <input type="text" name="lokasi" value="{{ $kegiatan->lokasi }}" class="form-control" required>
        </div>

        <div class="form-group mb-4">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4">{{ $kegiatan->deskripsi }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
