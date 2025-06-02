<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class AbsensiExport implements FromArray, WithHeadings, WithCustomStartCell, WithTitle
{
    protected $kegiatanId;

    public function __construct($kegiatanId)
    {
        $this->kegiatanId = $kegiatanId;
    }

    public function array(): array
    {
        $absensis = Absensi::where('kegiatan_id', $this->kegiatanId)
            ->with('pegawai')
            ->get();

        // Hitung rekap divisi
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

        // Data tabel absensi
        $data = [];
        foreach ($absensis as $index => $absen) {
            $data[] = [
                'No' => $index + 1,
                'Nama Pegawai' => $absen->pegawai->nama,
                'NIP' => "'" . $absen->pegawai->nip,
                'Divisi' => $absen->pegawai->divisi ?? '-',
                'Waktu Absen' => $absen->created_at->format('H:i:s'),
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        $kegiatan = Kegiatan::find($this->kegiatanId);
        $absensis = Absensi::where('kegiatan_id', $this->kegiatanId)->with('pegawai')->get();

        // Hitung rekap divisi
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

        $rekap = ["Rekap Jumlah Pegawai per Divisi:"];
        foreach ($divisiCounts as $divisi => $jumlah) {
            $rekap[] = "$divisi: $jumlah";
        }
        $rekap[] = "Total Pegawai: $totalPegawai";

        // Format heading
        $heading = [
            ["Laporan Absensi Kegiatan: {$kegiatan->nama_kegiatan}"],
            ["Tanggal: " . \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y')],
            [], // baris kosong
        ];
        foreach ($rekap as $row) {
            $heading[] = [$row];
        }
        $heading[] = []; // baris kosong sebelum table
        $heading[] = ['No', 'Nama Pegawai', 'NIP', 'Divisi', 'Waktu Absen'];

        return $heading;
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function title(): string
    {
        $kegiatan = Kegiatan::find($this->kegiatanId);
        return $kegiatan->nama_kegiatan;
    }
}

