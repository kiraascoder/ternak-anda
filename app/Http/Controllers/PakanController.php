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
        $ternaks = Ternak::with('pemilik')->get();
        $pakans = Pakan::with('ternak', 'penyuluh')
            ->orderBy('tanggalRekomendasi', 'desc')
            ->paginate(12);
        return view('penyuluh.pakan', compact('pakans', 'ternaks'));
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
            'tanggalRekomendasi' => 'required|date|after_or_equal:today',
            'jenisPakan' => 'required|string|max:255',
            'kategori' => 'required|in:hijauan,konsentrat,suplemen,vitamin',
            'jumlah' => 'required|numeric|min:0.1',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'required|string|min:10',
        ], [
            'idTernak.required' => 'Pilih ternak yang akan diberi rekomendasi pakan.',
            'idTernak.exists' => 'Ternak yang dipilih tidak valid.',
            'tanggalRekomendasi.required' => 'Tanggal rekomendasi harus diisi.',
            'tanggalRekomendasi.date' => 'Format tanggal tidak valid.',
            'tanggalRekomendasi.after_or_equal' => 'Tanggal rekomendasi tidak boleh kurang dari hari ini.',
            'jenisPakan.required' => 'Jenis pakan harus diisi.',
            'jenisPakan.max' => 'Jenis pakan maksimal 255 karakter.',
            'kategori.required' => 'Kategori pakan harus dipilih.',
            'kategori.in' => 'Kategori pakan harus salah satu dari: hijauan, konsentrat, suplemen, vitamin.',
            'jumlah.required' => 'Jumlah pakan harus diisi.',
            'jumlah.numeric' => 'Jumlah pakan harus berupa angka.',
            'jumlah.min' => 'Jumlah pakan minimal 0.1.',
            'satuan.required' => 'Satuan pakan harus diisi.',
            'satuan.max' => 'Satuan pakan maksimal 50 karakter.',
            'deskripsi.required' => 'Deskripsi cara pemberian harus diisi.',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter.',
        ]);

        try {

            $pakan = Pakan::create([
                'idTernak' => $request->idTernak,
                'idPenyuluh' => Auth::id(),
                'tanggalRekomendasi' => $request->tanggalRekomendasi,
                'jenisPakan' => $request->jenisPakan,
                'kategori' => $request->kategori,
                'jumlah' => $request->jumlah,
                'satuan' => $request->satuan,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->route('penyuluh.pakan')
                ->with('success', 'Rekomendasi pakan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan rekomendasi pakan. Silakan coba lagi.');
        }
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
        try {
            $pakan = Pakan::findOrFail($id);
            $pakanName = $pakan->jenisPakan; // Store name before deletion

            $pakan->delete();

     
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Rekomendasi pakan '{$pakanName}' berhasil dihapus"
                ], 200);
            }

     
            return redirect()->route('penyuluh.pakan')
                ->with('success', "Rekomendasi pakan '{$pakanName}' berhasil dihapus");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rekomendasi pakan tidak ditemukan'
                ], 404);
            }

            return redirect()->route('penyuluh.pakan')
                ->with('error', 'Rekomendasi pakan tidak ditemukan');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus rekomendasi pakan'
                ], 500);
            }

            return redirect()->route('penyuluh.pakan')
                ->with('error', 'Terjadi kesalahan saat menghapus rekomendasi pakan');
        }
    }
}
