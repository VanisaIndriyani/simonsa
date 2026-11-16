<?php

namespace App\Http\Controllers;

use App\Services\RekapService;

class DashboardController extends Controller
{
    public function index(RekapService $rekap)
    {
        return view('dashboard', $rekap->dasbor());
    }
}
