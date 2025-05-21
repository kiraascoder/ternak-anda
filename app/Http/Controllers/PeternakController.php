<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeternakController extends Controller
{
    public function index()
    {
        return view('peternak.dashboard');
    }
}
