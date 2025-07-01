<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use Illuminate\Http\Request;
use App\Models\Ternak;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TernakController extends Controller
{
    /**
     * Display listing of ternak for authenticated peternak
     */
    public function index()
    {
        $ternakList = Ternak::where('idPemilik', Auth::user()->idUser)->get();
        $totalTernak = $ternakList->count();
        $ternakSehat = Ternak::where('idPemilik', Auth::user()->idUser)->where('status', 'sehat')->count();
        $ternakSakit = Ternak::where('idPemilik', Auth::user()->idUser)->where('status', 'sakit')->count();
        $ternakPerawatan = Ternak::where('idPemilik', Auth::user()->idUser)->where('status', 'perawatan')->count();
        $konsultasiSaya = Konsultasi::where('idPeternak', Auth::user()->idUser)->count();

        return view('peternak.ternak', compact(
            'ternakList',
            'totalTernak',
            'ternakSehat',
            'ternakSakit',
            'ternakPerawatan',
            'konsultasiSaya'
        ));
    }

    /**
     * Show the form for creating a new ternak
     */
    public function create()
    {
        return view('peternak.ternak.tambah');
    }

    /**
     * Store a newly created ternak in storage
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Peternak') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'namaTernak'     => 'required|string|max:100',
            'jenis'          => 'required|string|max:50',
            'jenis_kelamin'  => 'required|in:Jantan,Betina',
            'tanggalLahir'   => 'nullable|date',
            'berat'          => 'nullable|numeric|min:0',
            'status'         => 'required|in:sehat,sakit,perawatan',
            'asal'           => 'nullable|string|max:100',
            'keterangan'     => 'nullable|string|max:255',
            'fotoTernak'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('fotoTernak')) {
            $file = $request->file('fotoTernak');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('foto_ternak', $filename, 'public');
            $fotoPath = $filename;
        }

        $ternak = Ternak::create([
            'idPemilik'      => $user->idUser,
            'namaTernak'     => $validated['namaTernak'],
            'jenis'          => $validated['jenis'],
            'jenis_kelamin'  => $validated['jenis_kelamin'],
            'tanggalLahir'   => $validated['tanggalLahir'],
            'berat'          => $validated['berat'],
            'status'         => $validated['status'],
            'asal'           => $validated['asal'],
            'keterangan'     => $validated['keterangan'],
            'fotoTernak'     => $fotoPath,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data ternak berhasil disimpan.',
                'data' => $ternak
            ]);
        }

        return redirect()->route('peternak.ternak')->with('success', 'Data ternak berhasil disimpan.');
    }

    /**
     * Display the specified ternak
     */
    public function show($id)
    {
        $user = Auth::user();
        $ternak = Ternak::where('idTernak', $id)
            ->where('idPemilik', $user->idUser)
            ->firstOrFail();

        return view('peternak.ternak.detail', compact('ternak'));
    }

    /**
     * Show the form for editing the specified ternak
     */
    public function edit($id)
    {
        $user = Auth::user();
        $ternak = Ternak::where('idTernak', $id)
            ->where('idPemilik', $user->idUser)
            ->firstOrFail();

        return view('peternak.ternak.edit', compact('ternak'));
    }

    /**
     * Update the specified ternak in storage
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'Peternak') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $ternak = Ternak::where('idTernak', $id)
            ->where('idPemilik', $user->idUser)
            ->firstOrFail();

        $validated = $request->validate([
            'namaTernak'     => 'required|string|max:100',
            'jenis'          => 'required|string|max:50',
            'jenis_kelamin'  => 'required|in:Jantan,Betina',
            'tanggalLahir'   => 'nullable|date',
            'berat'          => 'nullable|numeric|min:0',
            'status'         => 'required|in:sehat,sakit,perawatan',
            'asal'           => 'nullable|string|max:100',
            'keterangan'     => 'nullable|string|max:255',
            'fotoTernak'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle foto upload
        if ($request->hasFile('fotoTernak')) {
            // Delete old photo if exists
            if ($ternak->fotoTernak && Storage::disk('public')->exists('foto_ternak/' . $ternak->fotoTernak)) {
                Storage::disk('public')->delete('foto_ternak/' . $ternak->fotoTernak);
            }

            // Upload new photo
            $file = $request->file('fotoTernak');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('foto_ternak', $filename, 'public');
            $validated['fotoTernak'] = $filename;
        }

        $ternak->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data ternak berhasil diupdate.',
                'data' => $ternak->fresh()
            ]);
        }

        return redirect()->route('peternak.ternak')->with('success', 'Data ternak berhasil diupdate.');
    }

    /**
     * Remove the specified ternak from storage
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $ternak = Ternak::where('idTernak', $id)
                ->where('idPemilik', $user->idUser)
                ->firstOrFail();

            // Delete photo if exists
            if ($ternak->fotoTernak && Storage::disk('public')->exists('foto_ternak/' . $ternak->fotoTernak)) {
                Storage::disk('public')->delete('foto_ternak/' . $ternak->fotoTernak);
            }

            $ternak->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data ternak berhasil dihapus.'
                ]);
            }

            return redirect()->route('peternak.ternak')->with('success', 'Data ternak berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data ternak: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('peternak.ternak')->with('error', 'Gagal menghapus data ternak.');
        }
    }

    /**
     * Get ternak data for AJAX requests
     */
    public function getTernakData($id)
    {
        try {
            $user = Auth::user();
            $ternak = Ternak::where('idTernak', $id)
                ->where('idPemilik', $user->idUser)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $ternak
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data ternak tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Export ternak data (for future implementation)
     */
    public function export()
    {
        // TODO: Implement export functionality
        return response()->json([
            'success' => false,
            'message' => 'Fitur export belum tersedia.'
        ]);
    }
}
