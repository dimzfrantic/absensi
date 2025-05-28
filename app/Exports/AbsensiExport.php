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

        $data = [];

        foreach ($absensis as $index => $absen) {
            $data[] = [
                'No' => $index + 1,
                'Nama Pegawai' => $absen->pegawai->nama,
                'NIP' => $absen->pegawai->nip,
                'Waktu Absen' => $absen->created_at->format('H:i:s'),
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        $kegiatan = Kegiatan::find($this->kegiatanId);
        $absenCount = Absensi::where('kegiatan_id', $this->kegiatanId)->count();

        return [
            ["Laporan Absensi Kegiatan: {$kegiatan->nama_kegiatan}"],
            ["Tanggal: " . \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y')],
            ["Jumlah Pegawai Hadir: {$absenCount}"],
            [], // baris kosong sebelum heading table
            ['No', 'Nama Pegawai', 'NIP', 'Waktu Absen'],
        ];
    }

    public function startCell(): string
    {
        return 'A1'; // mulai dari sel A1
    }

    public function title(): string
    {
        $kegiatan = Kegiatan::find($this->kegiatanId);
        return $kegiatan->nama_kegiatan;
    }
}

