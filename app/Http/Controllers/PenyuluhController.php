<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Laporan;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenyuluhController extends Controller
{
    public function index()
    {
        $ternaks = Ternak::with('pemilik')->get();
        $totalTernak = Ternak::all()->count();
        $ternakSehat = Ternak::where('status', 'sehat')->count();
        $ternakSakit = Ternak::where('status', 'sakit')->count();
        $konsultasiAktif = Konsultasi::where('status', 'berlangsung')->count();
        return view('penyuluh.dashboard', compact('totalTernak', 'ternakSehat', 'ternakSakit', 'konsultasiAktif', 'ternaks'));
    }

    public function profile()
    {
        $userInfo = Auth::user();

        return view('penyuluh.profile', compact('userInfo'));
    }
    public function ternak()
    {
        $ternakList = Ternak::with('pemilik')->get();
        $totalTernak = Ternak::all()->count();
        $ternakSehat = Ternak::where('status', 'sehat')->count();
        $ternakSakit = Ternak::where('status', 'sakit')->count();
        $ternakPerawatan = Ternak::where('status', 'perawatan')->count();
        return view('penyuluh.ternak', compact('ternakList', 'totalTernak', 'ternakSehat', 'ternakSakit', 'ternakPerawatan'));
    }
}
