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
        $user = Auth::user();
        $konsultasis = Konsultasi::with(['penyuluh', 'ternak', 'peternak'])
            ->where('idPeternak', $user->idUser)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalKonsultasi = Konsultasi::where('idPeternak', Auth::id())->count();
        $konsultasiBerlangsung = Konsultasi::where('idPeternak', Auth::id())->where('status', 'berlangsung')->count();
        $konsultasiSelesai = Konsultasi::where('idPeternak', Auth::id())->where('status', 'selesai')->count();
        $konsultasiPending = Konsultasi::where('idPeternak', Auth::id())->where('status', 'pending')->count();
        $penyuluhList = User::where('role', 'Penyuluh')->get();
        $ternakList = Ternak::where('idPemilik', Auth::id())->get();
        return view('peternak.konsultasi', compact('konsultasis', 'totalKonsultasi', 'konsultasiBerlangsung', 'konsultasiSelesai', 'konsultasiPending', 'penyuluhList', 'ternakList'));
    }
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
            'idPeternak'     => 'required|exists:users,idUser',
            'idPenyuluh'     => 'required|exists:users,idUser',
            'idTernak'       => 'required|exists:ternak,idTernak',
            'judul_konsultasi' => 'required|string|max:255',
            'kategori'       => 'required|in:kesehatan,nutrisi,breeding,perawatan,umum',
            'status'         => 'required|in:pending,berlangsung,selesai,dibatalkan',
            'deskripsi'      => 'required|string',
            'foto_ternak'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_ternak')) {
            $file = $request->file('foto_ternak');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('foto_ternak', $filename, 'public');
            $fotoPath = $filename;
        }

        $konsultasi = Konsultasi::create([
            'idPeternak' => $validated['idPeternak'],
            'idPenyuluh' => $validated['idPenyuluh'],
            'idTernak' => $validated['idTernak'],
            'judul_konsultasi' => $validated['judul_konsultasi'],
            'kategori' => $validated['kategori'],
            'status' => $validated['status'],
            'deskripsi' => $validated['deskripsi'],
            'foto_ternak' => $fotoPath,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Konsultasi berhasil disimpan.',
                'data' => $konsultasi
            ]);
        }

        return redirect()->route('peternak.konsultasi')->with('success', 'Data konsultasi berhasil disimpan.');
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
