@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Pegawai</h1>

    <div class="row mb-3">
        <div class="col-md-6">
            <form method="GET" action="{{ route('pegawai.index') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIP..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('pegawai.create') }}" class="btn btn-success">Tambah Pegawai</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIP</th>
                <th>QR Code</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawais as $pegawai)
                <tr>
                    <td>{{ $pegawai->nama }}</td>
                    <td>{{ $pegawai->nip }}</td>
                    <td><img src="{{ asset('storage/qrcodes/'.$pegawai->qrcode) }}" width="100"></td>
                    <td>
                        <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Tombol Kembali ke Dashboard di bawah -->
    <div class="mt-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>
@endsection
