<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;

class InformasiController extends Controller
{
    public function index()
    {
        $informasiList = Informasi::latest()->paginate(10);
        return view('informasi.index', compact('informasiList'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10',
            'tanggal_kegiatan' => 'required|date',
            'kategori' => 'required|string|in:berita,pengumuman,tips,panduan',
            'lokasi' => 'required|string|max:255',
            'idPenyuluh' => 'required|exists:users,idUser',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // 10MB max
        ], [
            'judul.required' => 'Judul wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'kategori.required' => 'Kategori wajib diisi',
            'kategori.in' => 'Kategori tidak valid',
            'tanggal_kegiatan.required' => 'Tanggal kegiatan wajib diisi',
            'tanggal_kegiatan.date' => 'Format tanggal tidak valid',
            'lokasi.required' => 'Lokasi wajib diisi',
            'lokasi.max' => 'Lokasi maksimal 255 karakter',
            'idPenyuluh.required' => 'Penyuluh wajib dipilih',
            'idPenyuluh.exists' => 'Penyuluh tidak ditemukan',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpg, jpeg, atau png',
            'foto.max' => 'Ukuran foto maksimal 10MB',
        ]);

        try {
            $fotoPath = null;

            // Handle file upload
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('informasi', $filename, 'public');
                $fotoPath = $filename;
            }

            // Create informasi
            $informasi = Informasi::create([
                'judul' => $request->input('judul'),
                'deskripsi' => $request->input('deskripsi'),
                'tanggal_kegiatan' => $request->input('tanggal_kegiatan'),
                'lokasi' => $request->input('lokasi'),
                'kategori' => $request->input('kategori'),
                'idPenyuluh' => $request->input('idPenyuluh'),
                'foto' => $fotoPath,
            ]);

            // Return JSON response for AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Informasi berhasil disimpan',
                    'data' => $informasi
                ]);
            }

            return redirect()->route('admin.informasi.index')->with('success', 'Informasi berhasil disimpan.');
        } catch (\Exception $e) {
            // Delete uploaded file if exists and there's an error
            if ($fotoPath && Storage::disk('public')->exists('informasi/' . $fotoPath)) {
                Storage::disk('public')->delete('informasi/' . $fotoPath);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
                ], 500);
            }
        }
    }
    public function destroy($idInformasi)
    {
        try {
            $informasi = Informasi::findOrFail($idInformasi);

            if ($informasi->foto && Storage::disk('public')->exists('informasi/' . $informasi->foto)) {
                Storage::disk('public')->delete('informasi/' . $informasi->foto);
            }

            $informasi->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Informasi berhasil dihapus'
                ]);
            }

            return redirect()->route('admin.informasi')->with('success', 'Informasi berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }
    public function update(Request $request, $idInformasi)
    {
        $informasi = Informasi::findOrFail($idInformasi);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10',
            'tanggal_kegiatan' => 'required|date',
            'kategori' => 'required|string|in:berita,pengumuman,tips,panduan',
            'lokasi' => 'required|string|max:255',
            'idPenyuluh' => 'required|exists:users,idUser',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ], [
            'judul.required' => 'Judul wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'kategori.required' => 'Kategori wajib diisi',
            'kategori.in' => 'Kategori tidak valid',
            'tanggal_kegiatan.required' => 'Tanggal kegiatan wajib diisi',
            'tanggal_kegiatan.date' => 'Format tanggal tidak valid',
            'lokasi.required' => 'Lokasi wajib diisi',
            'lokasi.max' => 'Lokasi maksimal 255 karakter',
            'idPenyuluh.required' => 'Penyuluh wajib dipilih',
            'idPenyuluh.exists' => 'Penyuluh tidak ditemukan',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpg, jpeg, atau png',
            'foto.max' => 'Ukuran foto maksimal 10MB',
        ]);

        try {
            $fotoPath = $informasi->foto;

            // Handle file upload
            if ($request->hasFile('foto')) {
                // Delete old file if exists
                if ($fotoPath && Storage::disk('public')->exists('informasi/' . $fotoPath)) {
                    Storage::disk('public')->delete('informasi/' . $fotoPath);
                }

                $file = $request->file('foto');
                $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('informasi', $filename, 'public');
                $fotoPath = $filename;
            }

            // Update informasi
            $informasi->update([
                'judul' => $request->input('judul'),
                'deskripsi' => $request->input('deskripsi'),
                'tanggal_kegiatan' => $request->input('tanggal_kegiatan'),
                'lokasi' => $request->input('lokasi'),
                'kategori' => $request->input('kategori'),
                'idPenyuluh' => $request->input('idPenyuluh'),
                'foto' => $fotoPath,
            ]);

            // Return JSON response for AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Informasi berhasil diperbarui',
                    'data' => $informasi
                ]);
            }

            return redirect()->route('admin.informasi.index')->with('success', 'Informasi berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }
}
