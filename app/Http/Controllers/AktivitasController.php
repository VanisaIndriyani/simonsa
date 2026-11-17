<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $data = LogAktivitas::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $kata = $request->string('q')->toString();
                $query->where(function ($w) use ($kata) {
                    $w->where('nama_user', 'like', "%{$kata}%")
                        ->orWhere('aktivitas', 'like', "%{$kata}%");
                });
            })
            ->when($request->filled('jenis'), fn ($query) => $query->where('jenis', $request->jenis))
            ->orderByDesc('waktu')
            ->orderByDesc('id_log')
            ->paginate(15)
            ->withQueryString();

        return view('aktivitas.index', compact('data'));
    }
}
