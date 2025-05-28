<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = \App\Models\Kegiatan::orderBy('tanggal', 'desc')->get();
        return view('admin.kegiatan.index', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tanggal'       => 'required|date',
            'lokasi'        => 'required',
            'deskripsi'     => 'nullable',
        ]);

        Kegiatan::create($request->all());

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan!');
    }
    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tanggal'       => 'required|date',
            'lokasi'        => 'required',
            'deskripsi'     => 'nullable',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update($request->all());

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->delete();

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
    public function selesai($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->status = 0;
        $kegiatan->save();

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diselesaikan.');
    }

}
