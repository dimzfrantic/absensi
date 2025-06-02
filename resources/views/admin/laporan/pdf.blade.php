<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi - {{ $kegiatan->nama_kegiatan }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        ul { margin: 0 0 10px 20px; padding: 0; }
    </style>
</head>
<body>
    <h2>Laporan Absensi: {{ $kegiatan->nama_kegiatan }}</h2>
    <p>Tanggal: {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</p>
    @php
        $divisiCounts = [];
        $totalPegawai = 0;
        foreach ($absensis as $absen) {
            $divisi = $absen->pegawai->divisi ?? '-';
            if (!isset($divisiCounts[$divisi])) {
                $divisiCounts[$divisi] = 0;
            }
            $divisiCounts[$divisi]++;
            $totalPegawai++;
        }
    @endphp
    <div>
        <strong>Rekap Jumlah Pegawai per Divisi:</strong>
        <ul>
            @foreach($divisiCounts as $divisi => $jumlah)
                <li>{{ $divisi }}: {{ $jumlah }}</li>
            @endforeach
            <li><strong>Total Pegawai: {{ $totalPegawai }}</strong></li>
        </ul>
    </div>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Pegawai</th>
                <th>NIP</th>
                <th>Divisi</th>
                <th>Waktu Absen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensis as $index => $absen)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $absen->pegawai->nama }}</td>
                <td>{{ $absen->pegawai->nip }}</td>
                <td>{{ $absen->pegawai->divisi ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($absen->created_at)->format('H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
