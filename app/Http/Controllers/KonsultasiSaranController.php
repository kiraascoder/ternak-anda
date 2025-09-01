<?php

namespace App\Http\Controllers;

use App\Models\KonsultasiSaran;
use Illuminate\Http\Request;

class KonsultasiSaranController extends Controller
{
    public function storeSaran(Request $request, $id)
    {
        $validated = $request->validate([
            'judul'  => 'nullable|string|max:255',
            'isi'    => 'required|string|max:1000',
        ]);

        $saran = KonsultasiSaran::create([
            'idKonsultasi' => $id,
            'idPenyuluh'   => auth()->id(),
            'judul'         => $validated['judul'] ?? null,
            'isi'           => $validated['isi'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Saran berhasil disimpan.',
            'data'    => $saran,
        ]);
    }
    public function listSaran($id)
    {
        $sarans = KonsultasiSaran::with('user')
            ->where('idKonsultasi', $id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($s) => [
                'judul'      => $s->judul,
                'isi'        => $s->isi,
                'created_at' => optional($s->created_at)->toIso8601String(),
                'penyuluh'   => optional($s->user)->name ?? 'Penyuluh',
            ]);

        return response()->json($sarans);
    }
}
