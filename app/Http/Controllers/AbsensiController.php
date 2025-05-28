<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Kegiatan;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::where('status', 1)->orderBy('tanggal', 'desc')->get();
        return view('absensi.index', compact('kegiatans'));

    }

    public function scan(Request $request)
    {
        $nip = $request->input('nip');
        $kegiatan_id = $request->input('kegiatan_id');

        // Validasi input
        if (!$nip || !$kegiatan_id) {
            return response()->json(['success' => false, 'message' => 'Data tidak lengkap'], 422);
        }

        $pegawai = Pegawai::where('nip', $nip)->first();
        if (!$pegawai) {
            return response()->json(['success' => false, 'message' => 'Pegawai tidak ditemukan'], 404);
        }

        // Cek kegiatan
        $kegiatan = Kegiatan::find($kegiatan_id);
        if (!$kegiatan) {
            return response()->json(['success' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
        }

        // Cek apakah sudah absen
        $sudahAbsen = Absensi::where('pegawai_id', $pegawai->id)
                            ->where('kegiatan_id', $kegiatan_id)
                            ->exists();

        if ($sudahAbsen) {
            return response()->json(['success' => false, 'message' => 'Pegawai sudah melakukan absensi']);
        }

        // Simpan absensi
        Absensi::create([
            'pegawai_id' => $pegawai->id,
            'kegiatan_id' => $kegiatan_id,
            'waktu_absen' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Absensi berhasil']);
    }

    //Log realtime
    public function getLogHariIni($kegiatanId)
    {
        $absensis = \App\Models\Absensi::with('pegawai')
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->where('kegiatan_id', $kegiatanId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($absensis);
    }

}
