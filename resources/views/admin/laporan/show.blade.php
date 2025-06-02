@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Laporan Absensi: {{ $kegiatan->nama_kegiatan }}</h2>
    <p>Tanggal: {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</p>

    @if($absensis->isEmpty())
        <div class="alert alert-info">Belum ada pegawai yang absen.</div>
    @else
        <p><strong>Jumlah Pegawai yang Hadir: {{ $absensis->count() }}</strong></p>
        <div class="mb-3 text-end">
            <a href="{{ route('laporan.downloadPdfDetail', $kegiatan->id) }}" class="btn btn-danger">Download PDF</a>
            <a href="{{ route('laporan.downloadExcelDetail', $kegiatan->id) }}" class="btn btn-success">Download Excel</a>
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Kembali ke Daftar Kegiatan</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pegawai</th>
                    <th>NIP</th>
                    <th>Waktu Absen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensis as $index => $absen)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $absen->pegawai->nama }}</td>
                    <td>{{ $absen->pegawai->nip }}</td>
                    <td>{{ \Carbon\Carbon::parse($absen->created_at)->format('H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
