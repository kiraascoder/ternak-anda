<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Ternak;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    public function index()
    {
        $konsultasis = Konsultasi::where('idPeternak', Auth::id())
            ->with(['penyuluh', 'ternak', 'peternak'])
            ->orderBy('tanggalKonsultasi', 'desc')
            ->get();
        return view('peternak.konsultasi.index', compact('konsultasis'));
    }


    public function penyuluhView()
    {
        $konsultasis = Konsultasi::with(['penyuluh', 'ternak', 'peternak'])->get();
        return view('penyuluh.konsultasi', compact('konsultasis'));
    }



    public function KonsultasiStoreView()
    {
        $peternak = Auth::user();
        $penyuluhs = User::where('role', 'Penyuluh')->get();
        $ternaks = Ternak::where('idPemilik', Auth::id())->get();
        return view('peternak.konsultasi.tambah', compact('peternak', 'penyuluhs', 'ternaks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idPenyuluh' => 'required|exists:users,idUser',
            'idTernak' => 'required|exists:ternak,idTernak',
            'tanggalKonsultasi' => 'required|date',
            'keluhan' => 'required|string',
        ]);

        \App\Models\Konsultasi::create([
            'idPeternak' => Auth::id(),
            'idPenyuluh' => $request->idPenyuluh,
            'idTernak' => $request->idTernak,
            'tanggalKonsultasi' => $request->tanggalKonsultasi,
            'keluhan' => $request->keluhan,
        ]);

        return redirect()->route('konsultasi.index')->with('success', 'Konsultasi berhasil disimpan.');
    }
    public function destroy($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        $konsultasi->delete();
        return redirect()->route('konsultasi.index')->with('success', 'Konsultasi berhasil dihapus.');
    }


    // Penyuluh Controller

    public function konsultasiEditView($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        return view('penyuluh.edit.konsultasi', compact('konsultasi'));
    }

    public function storeResponPenyuluh(Request $request, $id)
    {
        $request->validate([
            'respon' => 'required|string',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);
        $konsultasi->respon = $request->respon;
        $konsultasi->save();

        return redirect()->route('penyuluh.konsultasi')->with('success', 'Respon berhasil ditambahkan.');
    }
}
