<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Debug log untuk melihat data yang masuk
            Log::info('Laporan store called', [
                'user_id' => Auth::id(),
                'user' => Auth::user(),
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'idPeternak' => 'required|exists:users,idUser',
                'idTernak' => 'required|exists:ternak,idTernak',
                'tanggal_pemeriksaan' => 'required|date',
                'berat_badan' => 'nullable|numeric|min:0|max:9999.99',
                'suhu_tubuh' => 'required|numeric|min:35|max:45',
                'nafsu_makan' => 'required|in:baik,menurun,tidak_ada',
                'pernafasan' => 'required|in:normal,cepat,lambat,sesak',
                'kulit_bulu' => 'required|in:normal,kusam,luka,parasit',
                'mata_hidung' => 'required|in:normal,berair,bengkak,bernanah',
                'feses' => 'required|in:normal,encer,keras,berdarah',
                'aktivitas' => 'required|in:aktif,lesu,gelisah,lemas',
                'tindakan' => 'required|string|max:1000',
                'rekomendasi' => 'required|string|max:1000',
            ], [
                'idPeternak.required' => 'Pilih peternak terlebih dahulu.',
                'idPeternak.exists' => 'Peternak yang dipilih tidak valid.',
                'idTernak.required' => 'Pilih ternak terlebih dahulu.',
                'idTernak.exists' => 'Ternak yang dipilih tidak valid.',
                'tanggal_pemeriksaan.required' => 'Tanggal pemeriksaan harus diisi.',
                'tanggal_pemeriksaan.date' => 'Format tanggal pemeriksaan tidak valid.',
                'berat_badan.numeric' => 'Berat badan harus berupa angka.',
                'berat_badan.min' => 'Berat badan tidak boleh kurang dari 0.',
                'berat_badan.max' => 'Berat badan terlalu besar.',
                'suhu_tubuh.required' => 'Suhu tubuh harus diisi.',
                'suhu_tubuh.numeric' => 'Suhu tubuh harus berupa angka.',
                'suhu_tubuh.min' => 'Suhu tubuh minimal 35°C.',
                'suhu_tubuh.max' => 'Suhu tubuh maksimal 45°C.',
                'nafsu_makan.required' => 'Pilih kondisi nafsu makan.',
                'nafsu_makan.in' => 'Kondisi nafsu makan tidak valid.',
                'pernafasan.required' => 'Pilih kondisi pernafasan.',
                'pernafasan.in' => 'Kondisi pernafasan tidak valid.',
                'kulit_bulu.required' => 'Pilih kondisi kulit & bulu.',
                'kulit_bulu.in' => 'Kondisi kulit & bulu tidak valid.',
                'mata_hidung.required' => 'Pilih kondisi mata & hidung.',
                'mata_hidung.in' => 'Kondisi mata & hidung tidak valid.',
                'feses.required' => 'Pilih kondisi feses.',
                'feses.in' => 'Kondisi feses tidak valid.',
                'aktivitas.required' => 'Pilih kondisi aktivitas.',
                'aktivitas.in' => 'Kondisi aktivitas tidak valid.',
                'tindakan.required' => 'Tindakan yang dilakukan harus diisi.',
                'tindakan.max' => 'Tindakan tidak boleh lebih dari 1000 karakter.',
                'rekomendasi.required' => 'Rekomendasi perawatan harus diisi.',
                'rekomendasi.max' => 'Rekomendasi tidak boleh lebih dari 1000 karakter.',
            ]);

            Log::info('Validation passed', ['validated_data' => $validated]);

            // Verifikasi ternak sesuai dengan peternak
            $ternak = Ternak::where('idTernak', $validated['idTernak'])
                ->where('idPemilik', $validated['idPeternak'])
                ->first();

            if (!$ternak) {
                Log::warning('Ternak tidak sesuai dengan peternak', [
                    'idTernak' => $validated['idTernak'],
                    'idPeternak' => $validated['idPeternak']
                ]);

                return back()->withInput()->withErrors([
                    'idTernak' => 'Ternak yang dipilih tidak sesuai dengan peternak.'
                ]);
            }

            // Evaluasi kondisi kesehatan
            $kondisiKesehatan = $this->evaluateHealthCondition($validated);

            Log::info('Health condition evaluated', ['kondisi' => $kondisiKesehatan]);


            $currentUser = Auth::user();
            $currentUserId = $currentUser->idUser;

            Log::info('Current user info', [
                'user' => $currentUser,
                'idUser' => $currentUserId,
                'auth_id' => Auth::id() // This should return idUser value
            ]);

            // Buat laporan menggunakan Eloquent
            $laporan = Laporan::create([
                'idPenyuluh' => $currentUserId,
                'idPeternak' => $validated['idPeternak'],
                'idTernak' => $validated['idTernak'],
                'tanggal_pemeriksaan' => Carbon::parse($validated['tanggal_pemeriksaan']),
                'berat_badan' => $validated['berat_badan'],
                'suhu_tubuh' => $validated['suhu_tubuh'],
                'nafsu_makan' => $validated['nafsu_makan'],
                'pernafasan' => $validated['pernafasan'],
                'kulit_bulu' => $validated['kulit_bulu'],
                'mata_hidung' => $validated['mata_hidung'],
                'feses' => $validated['feses'],
                'aktivitas' => $validated['aktivitas'],
                'tindakan' => $validated['tindakan'],
                'rekomendasi' => $validated['rekomendasi'],
                'status_kesehatan' => $kondisiKesehatan,
            ]);

            Log::info('Laporan created successfully', [
                'laporan_id' => $laporan->id,
                'laporan' => $laporan->toArray()
            ]);

            // Log aktivitas
            $this->logActivity('create_health_report', [
                'laporan_id' => $laporan->id,
                'ternak_id' => $validated['idTernak'],
                'peternak_id' => $validated['idPeternak'],
                'status_kesehatan' => $kondisiKesehatan,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('penyuluh.laporan')
                ->with('success', 'Laporan kesehatan berhasil disimpan!')
                ->with('laporan_id', $laporan->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error saving health report', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan laporan: ' . $e->getMessage()
            ]);
        }
    }

    private function evaluateHealthCondition($data)
    {
        $healthScore = 0;
        $totalParams = 7;

        // Evaluasi suhu tubuh
        if ($data['suhu_tubuh'] >= 37.5 && $data['suhu_tubuh'] <= 39.5) {
            $healthScore++;
        }

        // Evaluasi nafsu makan
        if ($data['nafsu_makan'] === 'baik') {
            $healthScore++;
        }

        // Evaluasi pernafasan
        if ($data['pernafasan'] === 'normal') {
            $healthScore++;
        }

        // Evaluasi kulit & bulu
        if ($data['kulit_bulu'] === 'normal') {
            $healthScore++;
        }

        // Evaluasi mata & hidung
        if ($data['mata_hidung'] === 'normal') {
            $healthScore++;
        }

        // Evaluasi feses
        if ($data['feses'] === 'normal') {
            $healthScore++;
        }

        // Evaluasi aktivitas
        if ($data['aktivitas'] === 'aktif') {
            $healthScore++;
        }

        // Tentukan status kesehatan berdasarkan skor
        $healthPercentage = ($healthScore / $totalParams) * 100;

        if ($healthPercentage >= 85) {
            return 'sehat';
        } elseif ($healthPercentage >= 60) {
            return 'perlu_perhatian';
        } else {
            return 'sakit';
        }
    }

    private function logActivity($action, $data = [])
    {
        try {
            $currentUser = Auth::user();
            $currentUserId = $currentUser->idUser;

            DB::table('activity_logs')->insert([
                'user_id' => $currentUserId,
                'user_type' => 'penyuluh',
                'action' => $action,
                'data' => json_encode($data),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log activity: ' . $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $currentUser = Auth::user();
            $currentUserId = $currentUser->idUser;

            $laporans = Laporan::with(['ternak', 'peternak'])
                ->byPenyuluh($currentUserId)
                ->latest()
                ->paginate(10);

            $peternaks = User::where('role', 'peternak')
                ->select('idUser', 'nama')
                ->get();

            $ternaks = Ternak::select('idTernak', 'namaTernak', 'jenis', 'idPemilik')->get();

            return view('penyuluh.laporan', compact('laporans', 'peternaks', 'ternaks'));
        } catch (\Exception $e) {
            Log::error('Error loading laporan index', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat memuat data.']);
        }
    }


    public function getDetail($id)
    {
        try {
            // Debug: Log the request
            \Log::info('Fetching report detail', ['id' => $id, 'user_id' => Auth::id()]);

            $laporan = Laporan::with(['ternak', 'peternak', 'penyuluh'])
                ->where('id', $id)
                ->byPenyuluh(Auth::id())
                ->first();

            if (!$laporan) {
                \Log::warning('Report not found', ['id' => $id, 'user_id' => Auth::id()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan'
                ], 404);
            }

            // Calculate health score
            $healthScore = 0;
            $totalParams = 7;

            // Evaluasi suhu tubuh (normal: 37.5-39.5°C)
            if ($laporan->suhu_tubuh >= 37.5 && $laporan->suhu_tubuh <= 39.5) {
                $healthScore++;
            }

            // Evaluasi parameter lainnya
            if ($laporan->nafsu_makan === 'baik') $healthScore++;
            if ($laporan->pernafasan === 'normal') $healthScore++;
            if ($laporan->kulit_bulu === 'normal') $healthScore++;
            if ($laporan->mata_hidung === 'normal') $healthScore++;
            if ($laporan->feses === 'normal') $healthScore++;
            if ($laporan->aktivitas === 'aktif') $healthScore++;

            // Text mapping untuk display
            $textMappings = [
                'nafsu_makan' => [
                    'baik' => 'Baik',
                    'menurun' => 'Menurun',
                    'tidak_ada' => 'Tidak Ada'
                ],
                'pernafasan' => [
                    'normal' => 'Normal',
                    'cepat' => 'Cepat',
                    'lambat' => 'Lambat',
                    'sesak' => 'Sesak'
                ],
                'kulit_bulu' => [
                    'normal' => 'Normal',
                    'kusam' => 'Kusam',
                    'luka' => 'Ada Luka',
                    'parasit' => 'Ada Parasit'
                ],
                'mata_hidung' => [
                    'normal' => 'Normal',
                    'berair' => 'Berair',
                    'bengkak' => 'Bengkak',
                    'bernanah' => 'Bernanah'
                ],
                'feses' => [
                    'normal' => 'Normal',
                    'encer' => 'Encer/Diare',
                    'keras' => 'Keras',
                    'berdarah' => 'Berdarah'
                ],
                'aktivitas' => [
                    'aktif' => 'Aktif',
                    'lesu' => 'Lesu',
                    'gelisah' => 'Gelisah',
                    'lemas' => 'Lemas'
                ],
                'status_kesehatan' => [
                    'sehat' => 'Sehat',
                    'perlu_perhatian' => 'Perlu Perhatian',
                    'sakit' => 'Sakit'
                ]
            ];

            // Format data untuk modal
            $data = [
                'id' => $laporan->id,
                'peternak_name' => $laporan->peternak->nama ?? '-',
                'peternak_address' => $laporan->peternak->alamat ?? $laporan->peternak->email ?? '-',
                'ternak_name' => $laporan->ternak->namaTernak ?? '-',
                'ternak_type' => $laporan->ternak->jenis ?? '-',
                'berat_badan' => $laporan->berat_badan,
                'tanggal_pemeriksaan' => $laporan->tanggal_pemeriksaan->toISOString(),
                'penyuluh_name' => $laporan->penyuluh->nama ?? '-',
                'suhu_tubuh' => $laporan->suhu_tubuh,
                'nafsu_makan' => $laporan->nafsu_makan,
                'pernafasan' => $laporan->pernafasan,
                'kulit_bulu' => $laporan->kulit_bulu,
                'mata_hidung' => $laporan->mata_hidung,
                'feses' => $laporan->feses,
                'aktivitas' => $laporan->aktivitas,
                'tindakan' => $laporan->tindakan,
                'rekomendasi' => $laporan->rekomendasi,
                'status_kesehatan' => $laporan->status_kesehatan,
                'health_score' => $healthScore,
                'health_percentage' => round(($healthScore / $totalParams) * 100),
                'created_at' => $laporan->created_at->toISOString(),

                // Text versions untuk display
                'nafsu_makan_text' => $textMappings['nafsu_makan'][$laporan->nafsu_makan] ?? $laporan->nafsu_makan,
                'pernafasan_text' => $textMappings['pernafasan'][$laporan->pernafasan] ?? $laporan->pernafasan,
                'kulit_bulu_text' => $textMappings['kulit_bulu'][$laporan->kulit_bulu] ?? $laporan->kulit_bulu,
                'mata_hidung_text' => $textMappings['mata_hidung'][$laporan->mata_hidung] ?? $laporan->mata_hidung,
                'feses_text' => $textMappings['feses'][$laporan->feses] ?? $laporan->feses,
                'aktivitas_text' => $textMappings['aktivitas'][$laporan->aktivitas] ?? $laporan->aktivitas,
                'status_kesehatan_text' => $textMappings['status_kesehatan'][$laporan->status_kesehatan] ?? $laporan->status_kesehatan,
            ];

            \Log::info('Report detail fetched successfully', ['id' => $id]);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching health report detail: ' . $e->getMessage(), [
                'report_id' => $id,
                'user_id' => Auth::id(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print health report (HTML view for printing)
     */
    public function printReport($id)
    {
        try {
            $laporan = Laporan::with(['ternak', 'peternak', 'penyuluh'])
                ->where('id', $id)
                ->byPenyuluh(Auth::id())
                ->first();

            if (!$laporan) {
                return redirect()->route('penyuluh.laporan.index')
                    ->withErrors(['error' => 'Laporan tidak ditemukan.']);
            }

            return view('penyuluh.laporan.print', compact('laporan'));
        } catch (\Exception $e) {
            \Log::error('Error printing health report: ' . $e->getMessage(), [
                'report_id' => $id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('penyuluh.laporan.index')
                ->withErrors(['error' => 'Terjadi kesalahan saat mencetak laporan.']);
        }
    }

    /**
     * Export health report as PDF
     */
    public function exportPdf($id)
    {
        try {
            $laporan = Laporan::with(['ternak', 'peternak', 'penyuluh'])
                ->where('id', $id)
                ->byPenyuluh(Auth::id())
                ->first();

            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan'
                ], 404);
            }

            // If you have PDF library like DomPDF installed
            // $pdf = PDF::loadView('penyuluh.laporan.pdf', compact('laporan'));
            // return $pdf->download("laporan_kesehatan_{$laporan->id}.pdf");

            // For now, redirect to print view
            return redirect()->route('penyuluh.laporan.print', $id);
        } catch (\Exception $e) {
            \Log::error('Error exporting health report as PDF: ' . $e->getMessage(), [
                'report_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengexport PDF'
            ], 500);
        }
    }



    public function destroy($id)
    {
        try {
            \Log::info('Attempting to delete health report', [
                'report_id' => $id,
                'user_id' => Auth::id()
            ]);

            // Find laporan dengan validasi ownership
            $laporan = Laporan::with(['ternak', 'peternak'])
                ->where('id', $id)
                ->byPenyuluh(Auth::id()) // Pastikan hanya penyuluh yang membuat bisa hapus
                ->first();

            if (!$laporan) {
                \Log::warning('Health report not found or unauthorized', [
                    'report_id' => $id,
                    'user_id' => Auth::id()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan atau Anda tidak memiliki akses untuk menghapus laporan ini.'
                ], 404);
            }

            // Backup info untuk log sebelum dihapus
            $reportInfo = [
                'id' => $laporan->id,
                'ternak_name' => $laporan->ternak->namaTernak ?? 'Unknown',
                'peternak_name' => $laporan->peternak->name ?? 'Unknown',
                'tanggal_pemeriksaan' => $laporan->tanggal_pemeriksaan->format('Y-m-d H:i:s'),
                'status_kesehatan' => $laporan->status_kesehatan
            ];


            $laporan->delete();

            \Log::info('Health report deleted successfully', [
                'deleted_report' => $reportInfo,
                'deleted_by' => Auth::id(),
                'deleted_at' => now()
            ]);

            // Log activity
            $this->logActivity('delete_health_report', [
                'deleted_report_id' => $id,
                'report_info' => $reportInfo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan kesehatan berhasil dihapus.',
                'deleted_report' => [
                    'id' => $reportInfo['id'],
                    'ternak_name' => $reportInfo['ternak_name'],
                    'tanggal_pemeriksaan' => $reportInfo['tanggal_pemeriksaan']
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting health report: ' . $e->getMessage(), [
                'report_id' => $id,
                'user_id' => Auth::id(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus laporan. Silakan coba lagi.'
            ], 500);
        }
    }

    public function bulkDestroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'integer|exists:laporan_kesehatan,id'
            ]);

            $ids = $validated['ids'];

            \Log::info('Attempting bulk delete of health reports', [
                'report_ids' => $ids,
                'user_id' => Auth::id()
            ]);


            $laporans = Laporan::with(['ternak', 'peternak'])
                ->whereIn('id', $ids)
                ->byPenyuluh(Auth::id())
                ->get();

            if ($laporans->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada laporan yang dapat dihapus atau Anda tidak memiliki akses.'
                ], 404);
            }

            if ($laporans->count() !== count($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa laporan tidak ditemukan atau tidak dapat diakses.'
                ], 403);
            }

            // Backup info sebelum dihapus
            $deletedReports = $laporans->map(function ($laporan) {
                return [
                    'id' => $laporan->id,
                    'ternak_name' => $laporan->ternak->namaTernak ?? 'Unknown',
                    'tanggal_pemeriksaan' => $laporan->tanggal_pemeriksaan->format('Y-m-d H:i:s')
                ];
            });

            // Delete all reports
            Laporan::whereIn('id', $ids)
                ->byPenyuluh(Auth::id())
                ->delete();

            \Log::info('Bulk delete completed successfully', [
                'deleted_count' => $laporans->count(),
                'deleted_reports' => $deletedReports->toArray(),
                'deleted_by' => Auth::id()
            ]);

            // Log activity
            $this->logActivity('bulk_delete_health_reports', [
                'deleted_count' => $laporans->count(),
                'deleted_ids' => $ids
            ]);

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$laporans->count()} laporan kesehatan.",
                'deleted_count' => $laporans->count(),
                'deleted_reports' => $deletedReports
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in bulk delete: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus laporan. Silakan coba lagi.'
            ], 500);
        }
    }


    public function restore($id)
    {
        try {

            $laporan = Laporan::withTrashed()
                ->where('id', $id)
                ->byPenyuluh(Auth::id())
                ->first();

            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan.'
                ], 404);
            }

            if (!$laporan->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak dalam kondisi terhapus.'
                ], 400);
            }

            $laporan->restore();

            \Log::info('Health report restored successfully', [
                'report_id' => $id,
                'restored_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dipulihkan.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error restoring health report: ' . $e->getMessage(), [
                'report_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memulihkan laporan.'
            ], 500);
        }
    }
}
