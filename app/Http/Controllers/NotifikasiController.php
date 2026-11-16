<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Notifikasi::with(['santri', 'pengguna'])->orderByDesc('created_at');
        if (! $request->user()->admin()) {
            $query->where('id_user', $request->user()->id_user);
        }
        if ($request->filled('status')) {
            $query->where('status_baca', $request->status);
        }
        $data = $query->paginate(12)->withQueryString();

        return view('notifikasi.index', compact('data'));
    }

    public function baca(Request $request, Notifikasi $notifikasi)
    {
        $this->izin($request, $notifikasi);
        $notifikasi->update(['status_baca' => 'sudah']);

        return back()->with('sukses', 'Notifikasi ditandai sudah dibaca.');
    }

    public function bacaSemua(Request $request)
    {
        $query = Notifikasi::where('status_baca', 'belum');
        if (! $request->user()->admin()) {
            $query->where('id_user', $request->user()->id_user);
        }
        $query->update(['status_baca' => 'sudah']);

        return back()->with('sukses', 'Semua notifikasi ditandai sudah dibaca.');
    }

    private function izin(Request $request, Notifikasi $notifikasi): void
    {
        if (! $request->user()->admin() && (int) $notifikasi->id_user !== (int) $request->user()->id_user) {
            abort(403);
        }
    }
}
