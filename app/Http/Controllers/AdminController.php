<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use App\Models\InformasiPakan;
use App\Models\Konsultasi;
use App\Models\Ternak;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $totalTernak = Ternak::all()->count();
        $ternaks = Ternak::with('pemilik')->get();
        return view('admin.dashboard', compact('totalTernak', 'ternaks'));
    }
    public function userView()
    {
        $userList = User::all();
        $totalUser = User::all()->count();
        $userAktif = User::where('status', 'aktif')->count();
        $userNonAktif = User::where('status', 'nonaktif')->count();
        $userSuspend = User::where('status', 'suspend')->count();
        return view('admin.user', compact('userList', 'totalUser', 'userAktif', 'userNonAktif', 'userSuspend'));
    }

    public function storeUser(Request $request)
    {
        try {

            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:Admin,Peternak,Penyuluh',
                'phone' => 'nullable|string|max:20',
                'status' => 'required|in:aktif,nonaktif,suspend'
            ]);

            $user = new User();
            $user->nama = $validated['nama'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->role = $validated['role'];
            $user->phone = $validated['phone'];
            $user->status = $validated['status'];
            $user->save();


            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User berhasil ditambahkan!',
                    'data' => $user
                ]);
            }

            return redirect()->route('admin.userView')->with('success', 'User berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat menyimpan data')->withInput();
        }
    }
    public function updateUser(Request $request, $idUser)
    {
        try {
            $user = User::findOrFail($idUser);
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($idUser, 'idUser')
                ],
                'role' => 'required|in:Admin,Peternak,Penyuluh',
                'phone' => 'nullable|string|max:20',
                'status' => 'required|string|max:255'
            ]);

            $user->nama = $validated['nama'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];
            $user->phone = $validated['phone'];
            $user->status = $validated['status'];

            if ($request->has('reset_password') && $request->reset_password) {
                $newPassword = Str::random(8);
                $user->password = Hash::make($newPassword);
            }

            $user->save();

            // Cek apakah request AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User berhasil diupdate!',
                    'data' => $user
                ]);
            }

            return redirect()->route('admin.userView')->with('success', 'User berhasil diupdate!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat mengupdate data')->withInput();
        }
    }


    public function deleteUser($idUser)
    {
        try {
            $user = User::find($idUser);
            if (!$user) {
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User tidak ditemukan atau sudah dihapus sebelumnya.'
                    ], 404);
                }

                return back()->with('error', 'User tidak ditemukan atau sudah dihapus sebelumnya.');
            }

            $userName = $user->nama;


            $user->delete();


            // Cek apakah request AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "User {$userName} berhasil dihapus!"
                ]);
            }

            return redirect()->route('admin.userView')->with('success', "User {$userName} berhasil dihapus!");
        } catch (\Exception $e) {


            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.'
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }
    public function storeInformasi(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        // $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('admin.userView');
    }
    public function storePakan(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        // $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('admin.userView');
    }

    public function ternak()
    {
        $ternakList = Ternak::all();
        $totalTernak = $ternakList->count();
        $ternakSehat = Ternak::where('status', 'sehat')->count();
        $ternakSakit = Ternak::where('status', 'sakit')->count();
        $ternakPerawatan = Ternak::where('status', 'perawatan')->count();
        $konsultasiSaya = Konsultasi::count();
        return view('admin.ternak', compact(
            'ternakList',
            'totalTernak',
            'ternakSehat',
            'ternakSakit',
            'ternakPerawatan',
            'konsultasiSaya'
        ));
    }

    public function informasi()
    {
        $informasiList = Informasi::latest()->paginate(10);
        $totalInformasi = Informasi::count();
        $informasiPublished = Informasi::count();
        $totalViews = $informasiList->count() * 100;
        $penyuluhs = User::where('role', 'Penyuluh')->get();

        return view('admin.informasi', compact(
            'informasiList',
            'totalInformasi',
            'informasiPublished',
            'totalViews',
            'penyuluhs'
        ));
    }

    public function pakan()
    {
        $informasiPakan = InformasiPakan::count();
        $pakanList = InformasiPakan::latest()->paginate(10);
        $draftPakan = InformasiPakan::where('is_published', 0)->count();
        $publishedPakan = InformasiPakan::where('is_published', 1)->count();
        return view('admin.pakan', compact('pakanList', 'informasiPakan', 'draftPakan', 'publishedPakan'));
    }
}
