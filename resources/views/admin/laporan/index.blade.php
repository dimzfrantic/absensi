@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Kegiatan</h1>

    <div class="mb-3 text-end">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Kegiatan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kegiatans as $kegiatan)
            <tr>
                <td>{{ $kegiatan->nama_kegiatan }}</td>
                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                <td>
                    <span class="badge {{ $kegiatan->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $kegiatan->status_label }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('laporan.show', $kegiatan->id) }}" class="btn btn-info btn-sm">Lihat Laporan</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
