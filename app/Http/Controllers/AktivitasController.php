<?php

namespace App\Http\Controllers;

use App\Models\AktivitasLogin;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $data = AktivitasLogin::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $kata = $request->string('q')->toString();
                $query->where(function ($w) use ($kata) {
                    $w->where('nama_user', 'like', "%{$kata}%")
                        ->orWhere('username', 'like', "%{$kata}%");
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->role))
            ->when($request->filled('jenis'), fn ($query) => $query->where('jenis', $request->jenis))
            ->orderByDesc('waktu')
            ->orderByDesc('id_aktivitas')
            ->paginate(15)
            ->withQueryString();

        return view('aktivitas.index', compact('data'));
    }
}
