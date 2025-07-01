<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeternakController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->idUser;
        $ternak = Ternak::where('idPemilik', $userId)->first();
        $totalTernak = Ternak::where('idPemilik', $userId)->count();
        $ternakSehat = Ternak::where('idPemilik', Auth::user()->idUser)->where('status', 'sehat')->count();
        $ternakSakit = Ternak::where('idPemilik', Auth::user()->idUser)->where('status', 'sakit')->count();
        $konsultasiSaya = Konsultasi::where('idPeternak', Auth::user()->idUser)->count();
        $recentTernakList = Ternak::where('idPemilik', Auth::user()->idUser)->orderBy('created_at', 'desc')->take(5)->get();
        return view('peternak.dashboard', compact('ternak', 'totalTernak', 'ternakSehat', 'ternakSakit', 'konsultasiSaya', 'recentTernakList'));
    }

    public function kesehatan()
    {
        return view('peternak.kesehatan');
    }

    public function konsultasi()
    {
        return view('peternak.konsultasi');
    }
    public function laporan()
    {
        return view('peternak.laporan');
    }
    public function profile()
    {
        $userInfo = Auth::user();
        $ternakSaya = Ternak::where('idPemilik', Auth::user()->idUser)->count();
        $konsultasiSaya = Konsultasi::where('idPeternak', Auth::user()->idUser)->count();
        return view('peternak.profile', compact('userInfo', 'ternakSaya', 'konsultasiSaya'));
    }
}
