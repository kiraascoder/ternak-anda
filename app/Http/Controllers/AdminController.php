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
        return view('admin.user', compact('userList'));
    }

    public function storeUser(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        // $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('admin.userView');
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
        $ternakList = Ternak::where('idPemilik', Auth::user()->idUser)->get();
        $totalTernak = $ternakList->count();
        $ternakSehat = Ternak::where('idPemilik', Auth::user()->idUser)->where('status', 'sehat')->count();
        $ternakSakit = Ternak::where('idPemilik', Auth::user()->idUser)->where('status', 'sakit')->count();
        $ternakPerawatan = Ternak::where('idPemilik', Auth::user()->idUser)->where('status', 'perawatan')->count();
        $konsultasiSaya = Konsultasi::where('idPeternak', Auth::user()->idUser)->count();
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
        $pakanList = InformasiPakan::latest()->paginate(10);
        return view('admin.pakan', compact('pakanList'));
    }
}
