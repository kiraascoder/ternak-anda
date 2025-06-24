<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerawatanController extends Controller
{
    public function index()
    {
        $perawatans = Perawatan::with('ternak', 'penyuluh')->get();
        return view('penyuluh.perawatan', compact('perawatans'));
    }

    public function perawatanStoreView()
    {
        $ternaks = Ternak::with('pemilik')->get();
        return view('penyuluh.tambah.perawatan', compact('ternaks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idTernak' => 'required|exists:ternak,idTernak',
            'tanggalPerawatan' => 'required|date',
            'jenisPerawatan' => 'required|string',
            'deskripsi' => 'required|string',
        ]);
        $perawatan = Perawatan::create([
            'idTernak' => $request->idTernak,
            'idPenyuluh' => Auth::id(),
            'tanggalPerawatan' => $request->tanggalPerawatan,
            'jenisPerawatan' => $request->jenisPerawatan,
            'deskripsi' => $request->deskripsi,
        ]);

        $perawatan->save();

        return redirect()->route('penyuluh.perawatan')->with('success', 'Perawatan berhasil disimpan.');
    }
    public function perawatanDetailView($id)
    {
        $perawatan = Perawatan::findOrFail($id);
        return view('penyuluh.detail.perawatan', compact('perawatan'));
    }

    public function perawatanEditView($id)
    {
        $perawatan = Perawatan::findOrFail($id);
        $ternaks = Ternak::with('pemilik')->get();
        return view('penyuluh.edit.perawatan', compact('ternaks', 'perawatan'));
    }

    public function edit(Request $request, $id)
    {
        $perawatan = Perawatan::findOrFail($id);

        $request->validate([
            'idTernak' => 'required|exists:ternak,idTernak',
            'tanggalPerawatan' => 'required|date',
            'jenisPerawatan' => 'required|string',
            'deskripsi' => 'required|string',

        ]);

        $perawatan->update([
            'idTernak' => $request->idTernak,
            'idPenyuluh' => Auth::id(),
            'tanggalPerawatan' => $request->tanggalPerawatan,
            'jenisPerawatan' => $request->jenisPerawatan,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('penyuluh.perawatan')->with('success', 'Perawatan berhasil diupdate.');
    }



    public function destroy($id)
    {
        $perawatan = Perawatan::findOrFail($id);
        $perawatan->delete();
        return redirect()->route('penyuluh.perawatan')->with('success', 'Perawatan berhasil dihapus.');
    }
}
