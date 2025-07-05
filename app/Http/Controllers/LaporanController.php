<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'idPeternak' => 'required|exists:users,id',
                'idTernak' => 'required|exists:ternaks,idTernak',
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


            $ternak = Ternak::where('idTernak', $validated['idTernak'])
                ->where('idPemilik', $validated['idPeternak'])
                ->first();

            if (!$ternak) {
                return back()->withInput()->withErrors([
                    'idTernak' => 'Ternak yang dipilih tidak sesuai dengan peternak.'
                ]);
            }


            $kondisiKesehatan = $this->evaluateHealthCondition($validated);

            // Buat laporan menggunakan Eloquent
            $laporan = Laporan::create([
                'idPenyuluh' => Auth::id(),
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

            // Log aktivitas
            $this->logActivity('create_health_report', [
                'laporan_id' => $laporan->id,
                'ternak_id' => $validated['idTernak'],
                'peternak_id' => $validated['idPeternak'],
                'status_kesehatan' => $kondisiKesehatan,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('penyuluh.laporan.index')
                ->with('success', 'Laporan kesehatan berhasil disimpan!')
                ->with('laporan_id', $laporan->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            // Handle other errors
            \Log::error('Error saving health report: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan laporan. Silakan coba lagi.'
            ]);
        }
    }

    private function evaluateHealthCondition($data)
    {
        $healthScore = 0;
        $totalParams = 7;


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
            DB::table('activity_logs')->insert([
                'user_id' => Auth::id(),
                'user_type' => 'penyuluh',
                'action' => $action,
                'data' => json_encode($data),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error tapi jangan mengganggu proses utama
            \Log::warning('Failed to log activity: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $laporans = Laporan::with(['ternak', 'peternak'])
            ->byPenyuluh(Auth::id())
            ->latest()
            ->paginate(10);

        $peternaks = User::where('role', 'peternak')
            ->select('idUser as idUser', 'nama as nama')
            ->get();

        $ternaks = Ternak::select('idTernak', 'namaTernak', 'jenis', 'idPemilik')->get();

        return view('penyuluh.laporan', compact('laporans', 'peternaks', 'ternaks'));
    }

    /**
     * Display the specified health report
     */
    public function show($id)
    {
        $laporan = Laporan::with(['ternak', 'peternak', 'penyuluh'])
            ->where('id', $id)
            ->byPenyuluh(Auth::id())
            ->first();

        if (!$laporan) {
            return redirect()->route('penyuluh.laporan.index')
                ->withErrors(['error' => 'Laporan tidak ditemukan.']);
        }

        return view('penyuluh.laporan.show', compact('laporan'));
    }
}
