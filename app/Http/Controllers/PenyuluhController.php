<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class PenyuluhController extends Controller
{
    public function index()
    {
        return view('penyuluh.dashboard');
    }

    public function profile()
    {
        return view('penyuluh.profile');
    }
    public function ternak()
    {
        return view('penyuluh.ternak');
    }
}
