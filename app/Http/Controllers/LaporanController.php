<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::all();
        return view('penyuluh.laporan', compact('laporans'));
    }

    public function laporanStoreView()
    {
        $ternaks = Ternak::with('pemilik')->get();
        return view('penyuluh.tambah.laporan', compact('ternaks'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'idTernak' => 'required|exists:ternak,idTernak',
            'idPeternak' => 'required|exists:users,idUser',
            'tanggalLaporan' => 'required|date',
            'kondisi' => 'required|string',
            'catatan' => 'required|string',
        ]);
        $laporan = Laporan::create([
            'idTernak' => $request->idTernak,
            'idPeternak' => $request->idPeternak,
            'idPenyuluh' => Auth::id(),
            'tanggalLaporan' => $request->tanggalLaporan,
            'kondisi' => $request->kondisi,
            'catatan' => $request->catatan,
        ]);

        $laporan->save();

        return redirect()->route('penyuluh.laporan')->with('success', 'Laporan berhasil disimpan.');
    }

    public function laporanDetailView($id)
    {
        $laporan = Laporan::findOrFail($id);
        return view('penyuluh.detail.laporan', compact('laporan'));
    }

    public function laporanEditView($id)
    {
        $laporan = Laporan::findOrFail($id);
        $ternaks = Ternak::with('pemilik')->get();
        return view('penyuluh.edit.laporan', compact('ternaks', 'laporan'));
    }

    public function edit(Request $request, $id)
    {
        $laporans = Laporan::findOrFail($id);

        $request->validate([
            'idTernak' => 'required|exists:ternak,idTernak',
            'idPeternak' => 'required|exists:users,idUser',
            'tanggalLaporan' => 'required|date',
            'kondisi' => 'required|string',
            'catatan' => 'required|string',

        ]);

        $laporans->update([
            'idTernak' => $request->idTernak,
            'idPeternak' => $request->idPeternak,
            'idPenyuluh' => Auth::id(),
            'tanggalLaporan' => $request->tanggalLaporan,
            'kondisi' => $request->kondisi,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('penyuluh.laporan')->with('success', 'Laporan berhasil diupdate.');
    }



    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();
        return redirect()->route('penyuluh.laporan')->with('success', 'Laporan berhasil dihapus.');
    }
}
