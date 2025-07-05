<?php

namespace App\Http\Controllers;

use App\Models\Ternak;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalTernak = Ternak::all()->count();
        $ternaks = Ternak::with('pemilik')->get();
        return view('admin.dashboard', compact('totalTernak', 'ternaks'));
    }
}
