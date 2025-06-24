<?php

namespace App\Http\Controllers;

use App\Models\Pakan;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PakanController extends Controller
{
    public function index()
    {
        $pakans = Pakan::with('ternak', 'penyuluh')->get();
        return view('penyuluh.pakan', compact('pakans'));
    }

    public function pakanStoreView()
    {
        $ternaks = Ternak::with('pemilik')->get();
        return view('penyuluh.tambah.pakan', compact('ternaks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idTernak' => 'required|exists:ternak,idTernak',
            'tanggalRekomendasi' => 'required|date',
            'jenisPakan' => 'required|string',
            'jumlah' => 'required|integer',
            'saran' => 'required|string',
        ]);
        $pakan = Pakan::create([
            'idTernak' => $request->idTernak,
            'idPenyuluh' => Auth::id(),
            'tanggalRekomendasi' => $request->tanggalRekomendasi,
            'jenisPakan' => $request->jenisPakan,
            'jumlah' => $request->jumlah,
            'saran' => $request->saran,
        ]);

        $pakan->save();

        return redirect()->route('penyuluh.pakan')->with('success', 'Pakan berhasil disimpan.');
    }


    public function pakanDetailView($id)
    {
        $pakan = Pakan::findOrFail($id);
        return view('penyuluh.detail.pakan', compact('pakan'));
    }

    public function pakanEditView($id)
    {
        $pakan = Pakan::findOrFail($id);
        $ternaks = Ternak::with('pemilik')->get();
        return view('penyuluh.edit.pakan', compact('ternaks', 'pakan'));
    }

    public function edit(Request $request, $id)
    {
        $pakans = Pakan::findOrFail($id);

        $request->validate([
            'idTernak' => 'required|exists:ternak,idTernak',
            'tanggalRekomendasi' => 'required|date',
            'jenisPakan' => 'required|string',
            'jumlah' => 'required|string',
            'saran' => 'required|string',

        ]);

        $pakans->update([
            'idTernak' => $request->idTernak,
            'idPenyuluh' => Auth::id(),
            'tanggalRekomendasi' => $request->tanggalRekomendasi,
            'jenisPakan' => $request->jenisPakan,
            'jumlah' => $request->jumlah,
            'saran' => $request->saran,
        ]);

        return redirect()->route('penyuluh.pakan')->with('success', 'Pakan berhasil diupdate.');
    }



    public function destroy($id)
    {
        $pakan = Pakan::findOrFail($id);
        $pakan->delete();
        return redirect()->route('penyuluh.pakan')->with('success', 'Pakan berhasil dihapus.');
    }
}
