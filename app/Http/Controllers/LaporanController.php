<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Absensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;

class LaporanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::orderBy('tanggal', 'desc')->get();
        return view('admin.laporan.index', compact('kegiatans'));
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $absensis = Absensi::with('pegawai')
                    ->where('kegiatan_id', $id)
                    ->orderBy('created_at', 'asc')
                    ->get();
        return view('admin.laporan.show', compact('kegiatan', 'absensis'));
    }

    public function downloadPdfDetail(Kegiatan $kegiatan)
    {
        $absensis = $kegiatan->absensis()->with('pegawai')->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('kegiatan', 'absensis'));
        return $pdf->download('laporan-absensi-'.$kegiatan->id.'.pdf');

    }

    public function downloadExcelDetail(Kegiatan $kegiatan)
    {
        return Excel::download(new AbsensiExport($kegiatan->id), 'laporan_absensi_'.$kegiatan->id.'.xlsx');
    }

}
