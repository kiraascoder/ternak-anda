<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Ternak;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function getDetail($id)
    {
        try {
            $konsultasi = Konsultasi::with(['peternak', 'ternak', 'penyuluh'])
                ->where('idKonsultasi', $id)
                ->first();

            if (!$konsultasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konsultasi tidak ditemukan'
                ], 404);
            }

            // Proses foto ternak
            $fotoTernak = null;
            if ($konsultasi->ternak && $konsultasi->ternak->fotoTernak) {
                $fotoTernak = $konsultasi->ternak->fotoTernak;

                // Pastikan path foto benar
                if (!str_starts_with($fotoTernak, '/')) {
                    $fotoTernak = '/storage/foto_ternak/' . $fotoTernak;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'idKonsultasi' => $konsultasi->idKonsultasi,
                    'judul_konsultasi' => $konsultasi->judul_konsultasi,
                    'kategori' => $konsultasi->kategori,
                    'deskripsi' => $konsultasi->deskripsi,
                    'status' => $konsultasi->status,
                    'foto_ternak' => $konsultasi->foto_ternak,
                    'created_at' => $konsultasi->created_at->format('d/m/Y H:i'),
                    'peternak' => [
                        'nama' => $konsultasi->peternak->nama ?? '',
                        'email' => $konsultasi->peternak->email ?? '',
                        'phone' => $konsultasi->peternak->phone ?? ''
                    ],
                    'ternak' => [
                        'nama_ternak' => $konsultasi->ternak->namaTernak ?? '',
                        'jenis' => $konsultasi->ternak->jenis ?? '',
                        'umur' => $konsultasi->ternak->umur ?? null,
                        'fotoTernak' => $fotoTernak // Path foto yang sudah diproses
                    ],
                    'penyuluh' => [
                        'nama' => $konsultasi->penyuluh->nama ?? '',
                        'email' => $konsultasi->penyuluh->email ?? ''
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error getting konsultasi detail: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil detail konsultasi'
            ], 500);
        }
    }
    public function penyuluhView()
    {
        $konsultasis = Konsultasi::where('idPenyuluh', Auth::id())->get();
        $detailKonsultasi = Konsultasi::with(['peternak', 'ternak'])
            ->where('idPenyuluh', Auth::id())
            ->first();
        if ($detailKonsultasi && $detailKonsultasi->ternak) {
            $tanggalLahir = Carbon::parse($detailKonsultasi->ternak->tanggalLahir);
            $detailKonsultasi->ternak->umur = $tanggalLahir->diffForHumans(null, true); // contoh: "2 tahun 3 bulan"
        }
        $totalKonsultasi = Konsultasi::where('idPenyuluh', Auth::id())->count();
        $totalKonsultasiHariIni = Konsultasi::where('idPenyuluh', Auth::id())->whereDate('created_at', Carbon::today())->count();
        $konsultasiBerlangsung = Konsultasi::where('idPenyuluh', Auth::id())->where('status', 'berlangsung')->count();
        $konsultasiSelesai = Konsultasi::where('idPenyuluh', Auth::id())->where('status', 'selesai')->count();
        $konsultasiPending = Konsultasi::where('idPenyuluh', Auth::id())->where('status', 'pending')->count();
        return view('penyuluh.konsultasi', compact('konsultasis', 'totalKonsultasi', 'konsultasiBerlangsung', 'konsultasiSelesai', 'konsultasiPending', 'totalKonsultasiHariIni', 'detailKonsultasi'));
    }
    public function updateStatus(Request $request, $id)
    {
        try {

            $request->validate([
                'status' => 'required|in:pending,berlangsung,selesai,dibatalkan'
            ]);


            $konsultasi = Konsultasi::where('idKonsultasi', $id)->first();

            if (!$konsultasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konsultasi tidak ditemukan'
                ], 404);
            }


            if (auth()->user()->idUser !== $konsultasi->idPenyuluh && auth()->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengubah status konsultasi ini'
                ], 403);
            }

            $oldStatus = $konsultasi->status;
            $newStatus = $request->status;


            $validTransitions = $this->getValidStatusTransitions($oldStatus);

            if (!in_array($newStatus, $validTransitions)) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat mengubah status dari '{$oldStatus}' menjadi '{$newStatus}'"
                ], 400);
            }

            // Update status
            DB::beginTransaction();

            $konsultasi->status = $newStatus;


            if ($newStatus === 'berlangsung' && !$konsultasi->idPenyuluh) {
                $konsultasi->idPenyuluh = auth()->user()->idUser;
            }

            $konsultasi->save();


            Log::info("Status konsultasi {$id} diubah dari {$oldStatus} menjadi {$newStatus} oleh user " . auth()->user()->idUser);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Status konsultasi berhasil diubah menjadi '{$newStatus}'",
                'data' => [
                    'id' => $konsultasi->idKonsultasi,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'updated_at' => $konsultasi->updated_at->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error("Error updating konsultasi status: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
    private function getValidStatusTransitions($currentStatus)
    {
        $transitions = [
            'pending' => ['berlangsung', 'dibatalkan'],
            'berlangsung' => ['selesai', 'dibatalkan'],
            'selesai' => [],
            'dibatalkan' => ['pending']
        ];

        return $transitions[$currentStatus] ?? [];
    }
}
