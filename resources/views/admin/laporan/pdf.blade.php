<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi - {{ $kegiatan->nama_kegiatan }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Absensi: {{ $kegiatan->nama_kegiatan }}</h2>
    <p>Tanggal: {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</p>
    <p><strong>Jumlah Pegawai yang Hadir: {{ $absensis->count() }}</strong></p>

    <table>
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
                <td>{{ \Carbon\Carbon::parse($absen->created_at)->format('d-m-Y H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
