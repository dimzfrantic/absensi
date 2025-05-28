@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-primary">Dashboard Admin</h1>
    <p class="lead mb-5">Selamat datang di halaman dashboard admin. Silakan pilih menu berikut untuk mengelola aplikasi.</p>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ route('pegawai.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-people-fill" style="font-size: 3rem; color: #0d6efd;"></i>
                        </div>
                        <h5 class="card-title">Data Pegawai</h5>
                        <p class="card-text text-muted">Kelola data pegawai, tambah, edit, atau hapus.</p>
                        <button class="btn btn-primary">Kelola Pegawai</button>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('kegiatan.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-success h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-calendar-event-fill" style="font-size: 3rem; color: #198754;"></i>
                        </div>
                        <h5 class="card-title">Daftar Kegiatan</h5>
                        <p class="card-text text-muted">Lihat dan kelola semua kegiatan yang telah dibuat.</p>
                        <button class="btn btn-success">Lihat Kegiatan</button>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
        <a href="{{ route('laporan.index') }}" class="text-decoration-none">
            <div class="card shadow-sm border-warning h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-file-earmark-text-fill" style="font-size: 3rem; color: #ffc107;"></i>
                    </div>
                    <h5 class="card-title">Laporan</h5>
                    <p class="card-text text-muted">Lihat laporan absensi tiap kegiatan yang telah selesai.</p>
                    <button class="btn btn-warning text-white">Lihat Laporan</button>
                </div>
            </div>
        </a>
    </div>

    </div>
</div>
@endsection
