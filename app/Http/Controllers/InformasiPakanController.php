<?php

namespace App\Http\Controllers;

use App\Models\InformasiPakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformasiPakanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // 10MB max
            'sumber' => 'nullable|string',
            'jenis_pakan' => 'required|string|in:hijauan,konsentrat,fermentasi,organik,limbah',
            'is_published' => 'required|integer|in:0,1',

        ], [
            'judul.required' => 'Judul wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpg, jpeg, atau png',
            'foto.max' => 'Ukuran foto maksimal 10MB',
            'sumber.string' => 'Sumber harus berupa teks',
            'is_published.in' => 'Status publikasi harus benar (0 atau 1)',
        ]);

        try {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('informasi-pakan', $filename, 'public');
                $fotoPath = $filename;
            }


            $pakan = InformasiPakan::create([
                'judul' => $request->input('judul'),
                'deskripsi' => $request->input('deskripsi'),
                'foto' => $fotoPath,
                'sumber' => $request->input('sumber'),
                'jenis_pakan' => $request->input('jenis_pakan'),
                'is_published' => $request->input('is_published'),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Informasi berhasil disimpan',
                    'data' => $pakan
                ]);
            }

            return redirect()->route('admin.pakan')->with('success', 'Pakan berhasil disimpan.');
        } catch (\Exception $e) {
            // Delete uploaded file if exists and there's an error
            if ($fotoPath && Storage::disk('public')->exists('pakan/' . $fotoPath)) {
                Storage::disk('public')->delete('pakan/' . $fotoPath);
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
                ], 500);
            }
        }
    }
    public function destroy($idPakan)
    {
        try {
            $informasi = InformasiPakan::findOrFail($idPakan);

            if ($informasi->foto && Storage::disk('public')->exists('pakan/' . $informasi->foto)) {
                Storage::disk('public')->delete('informasi/' . $informasi->foto);
            }

            $informasi->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Informasi Pakan berhasil dihapus'
                ]);
            }

            return redirect()->route('admin.pakan')->with('success', 'Informasi Pakan berhasil dihapus.');
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
    public function update(Request $request, $idPakan)
    {
        $informasi = InformasiPakan::findOrFail($idPakan);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // 10MB max
            'sumber' => 'nullable|string',
            'jenis_pakan' => 'required|string|in:hijauan,konsentrat,fermentasi,organik,limbah',
            'is_published' => 'required|integer|in:0,1',

        ], [
            'judul.required' => 'Judul wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpg, jpeg, atau png',
            'foto.max' => 'Ukuran foto maksimal 10MB',
            'sumber.string' => 'Sumber harus berupa teks',
            'is_published.in' => 'Status publikasi harus benar (0 atau 1)',
        ]);

        try {
            $fotoPath = $informasi->foto;

            // Handle file upload
            if ($request->hasFile('foto')) {
                // Delete old file if exists
                if ($fotoPath && Storage::disk('public')->exists('pakan/' . $fotoPath)) {
                    Storage::disk('public')->delete('pakan/' . $fotoPath);
                }

                $file = $request->file('foto');
                $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('informasi-pakan', $filename, 'public');
                $fotoPath = $filename;
            }


            $informasi->update([
                'judul' => $request->input('judul'),
                'deskripsi' => $request->input('deskripsi'),
                'sumber' => $request->input('sumber'),
                'jenis_pakan' => $request->input('jenis_pakan'),
                'is_published' => $request->input('is_published'),
                'foto' => $fotoPath,
            ]);


            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Informasi Pakan berhasil diperbarui',
                    'data' => $informasi
                ]);
            }

            return redirect()->route('admin.pakan')->with('success', 'Informasi Pakan berhasil diperbarui.');
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
    public function index()
    {
        $pakanList = InformasiPakan::all();
        return view('pakan.index', compact('pakanList'));
    }

    public function show($idPakan)
    {
        $pakan = InformasiPakan::findOrFail($idPakan);
        return view('pakan.detail-pakan', compact('pakan'));
    }
}
