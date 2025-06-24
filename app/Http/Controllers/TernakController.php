<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ternak;
use Illuminate\Support\Facades\Auth;

class TernakController extends Controller
{
    public function index()
    {
        $ternakSaya = Ternak::where('idPemilik', Auth::user()->idUser)->get();
        return view('peternak.ternak.index', compact('ternakSaya'));
    }
    public function ternakView()
    {
        return view('ternak.ternak');
    }
    public function ternakDetail($id)
    {
        $ternak = Ternak::findOrFail($id);
        return view('peternak.ternak.detail', compact('ternak'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'Peternak') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'namaTernak'    => 'required|string|max:100',
            'tanggalLahir'  => 'required|date',
            'berat'         => 'required|integer',
            'fotoTernak'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('fotoTernak')) {
            $path = $request->file('fotoTernak')->store('public/foto_ternak');
            $validated['fotoTernak'] = basename($path);
        }

        $ternak = Ternak::create([
            'idPemilik'     => $user->idUser,
            'namaTernak'    => $validated['namaTernak'],
            'tanggalLahir'  => $validated['tanggalLahir'],
            'berat'         => $validated['berat'],
            'fotoTernak'    => $validated['fotoTernak'] ?? null,
        ]);

        return response()->json([
            'message' => 'Data ternak berhasil disimpan.',
            'data' => $ternak
        ], 201);
    }
    public function ternakStoreView()
    {
        return view('peternak.ternak.tambah');
    }

    public function deleteTernak($id)
    {
        $ternak = Ternak::findOrFail($id);
        $ternak->delete();
        return redirect()->route('ternak.index')->with('success', 'Data ternak berhasil dihapus.');
    }
}
