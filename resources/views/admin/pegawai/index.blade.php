@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-3 text-end">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
    <h1>Daftar Pegawai</h1>

    <div class="row mb-3">
        <div class="col-md-6">
            <form method="GET" action="{{ route('pegawai.index') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIP..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>               
            </form>
            <a href="{{ route('pegawai.create') }}" class="btn btn-success mt-2">Tambah Pegawai</a>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('pegawai.downloadQRCodes') }}" class="btn btn-info ms-2 mt-2 mt-md-0">Download Semua QR Code</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Divisi</th>
                <th>QR Code</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawais as $index => $pegawai)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pegawai->nama }}</td>
                    <td>{{ $pegawai->nip }}</td>
                    <td>{{ $pegawai->divisi }}</td>
                    <td><img src="{{ asset('storage/qrcodes/'.$pegawai->qrcode) }}" width="100"></td>
                    <td>
                        <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</button>
                        </form>
                        <a href="{{ asset('storage/qrcodes/'.$pegawai->qrcode) }}" download class="btn btn-info btn-sm mt-1">Download QR</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
