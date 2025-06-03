<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Imports\PegawaiImport;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::query();

        // Kalau ada parameter 'search', filter berdasarkan nama atau nip
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nip', 'like', "%{$search}%");
        }

        $pegawais = $query->orderBy('nama')->get();

        return view('admin.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('admin.pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
        'nama' => 'required',
        'nip'  => 'required|numeric|unique:pegawais',
        'divisi' => 'required'
        ], [
            'nip.numeric' => 'NIP harus berupa angka.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique'   => 'NIP sudah terdaftar, silakan gunakan NIP lain.',
            'divisi.required' => 'Divisi wajib dipilih.',
        ]);

        // kode generate qrcode dan simpan data tetap sama
        $qrCodeName = $request->nip . '.png';
        $qrCodePath = storage_path('app/public/qrcodes/' . $qrCodeName);
        
        QrCode::format('png')
        ->size(200)
        ->margin(1)
        ->generate($request->nip, $qrCodePath);

        Pegawai::create([
            'nama'   => $request->nama,
            'nip'    => $request->nip,
            'divisi' => $request->divisi,
            'qrcode' => $qrCodeName,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('admin.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'nip'  => 'required|unique:pegawais,nip,' . $pegawai->id,
            'divisi' => 'required'
        ], [
            'divisi.required' => 'Divisi wajib dipilih.',
        ]);

        $pegawai->update([
            'nama' => $request->nama,
            'nip'  => $request->nip,
            'divisi' => $request->divisi,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        unlink(storage_path('app/public/qrcodes/' . $pegawai->qrcode));
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xls,xlsx'
        ]);

        $errorMessages = [];
        $imported = 0;

        $collection = \Maatwebsite\Excel\Facades\Excel::toArray([], $request->file('file_excel'));
        $rows = $collection[0];

        // Lewati baris header, mulai dari baris kedua
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // skip header

            // Ambil data berdasarkan urutan kolom (kolom 0 = Nama Pegawai, kolom 1 = NIP, kolom 2 = Divisi)
            $nama = isset($row[0]) ? trim($row[0]) : null;
            $nip = isset($row[1]) ? trim($row[1]) : null;
            $divisi = isset($row[2]) ? trim($row[2]) : null;

            // Validasi manual
            if (empty($nama)) {
                $errorMessages[] = 'Baris ' . ($index + 1) . ': Nama wajib diisi.';
                continue;
            }
            if (empty($nip)) {
                $errorMessages[] = 'Baris ' . ($index + 1) . ': NIP wajib diisi.';
                continue;
            }
            if (!is_numeric($nip)) {
                $errorMessages[] = 'Baris ' . ($index + 1) . ': NIP harus berupa angka.';
                continue;
            }
            if (empty($divisi)) {
                $errorMessages[] = 'Baris ' . ($index + 1) . ': Divisi wajib diisi.';
                continue;
            }
            if (\App\Models\Pegawai::where('nip', $nip)->exists()) {
                $errorMessages[] = 'Baris ' . ($index + 1) . ': NIP sudah terdaftar.';
                continue;
            }

            // Generate QR code
            $qrCodeName = $nip . '.png';
            $qrCodePath = storage_path('app/public/qrcodes/' . $qrCodeName);
            \QrCode::format('png')->size(200)->margin(1)->generate($nip, $qrCodePath);

            \App\Models\Pegawai::create([
                'nama'   => $nama,
                'nip'    => $nip,
                'divisi' => $divisi,
                'qrcode' => $qrCodeName,
            ]);
            $imported++;
        }

        $message = $imported > 0 ? "Berhasil import $imported pegawai." : null;

        return redirect()->route('pegawai.index')
            ->with('success', $message)
            ->withErrors($errorMessages);
    }

    public function downloadQRCodes()
    {
        $zip = new ZipArchive;
        $fileName = 'qrcodes_pegawai.zip';

        $qrcodePath = storage_path('app/public/qrcodes');
        $zipPath = storage_path('app/public/' . $fileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = glob($qrcodePath . '/*.png'); // anggap file qr code png

            foreach ($files as $file) {
                $relativeNameInZip = basename($file);
                $zip->addFile($file, $relativeNameInZip);
            }

            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat ZIP file.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
