@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Kegiatan</h1>

    <a href="{{ route('kegiatan.create') }}" class="btn btn-primary mb-3">Tambah Kegiatan</a>

    @if($kegiatans->isEmpty())
        <p>Belum ada kegiatan.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatans as $kegiatan)
                <tr>
                    <td>{{ $kegiatan->nama_kegiatan }}</td>
                    <td>{{ $kegiatan->tanggal->format('d-m-Y') }}</td>
                    <td>{{ $kegiatan->lokasi }}</td>
                    <td>{{ $kegiatan->deskripsi }}</td>
                    <td>
                        <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin ingin hapus kegiatan ini?')" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                        <form action="{{ route('kegiatan.selesai', $kegiatan->id) }}" method="POST" style="display:inline-block; margin-top:5px;">
                            @csrf
                            <button onclick="return confirm('Yakin ingin menyelesaikan kegiatan ini?')" class="btn btn-sm btn-secondary">Selesai</button>
                        </form>
                        @if($kegiatan->status == 1)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Selesai</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
<!-- Tombol Kembali ke Dashboard di bawah -->
    <div class="mt-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>
@endsection
