<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelanggaran;
use App\Models\Santri;
use App\Services\PoinService;
use App\Services\RekapService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request, RekapService $rekap)
    {
        if ($request->user()->role === 'keamanan') {
            abort(403);
        }

        $filter = $request->only(['q', 'id_kategori', 'kelas', 'dari', 'sampai']);
        $data = $rekap->query($filter)
            ->with(['santri', 'kategori', 'pengguna'])
            ->orderByDesc('tanggal_pelanggaran')
            ->orderByDesc('id_pelanggaran')
            ->paginate(12)
            ->withQueryString();

        $chart = $rekap->grafik($filter);
        $kategori = KategoriPelanggaran::orderBy('nama_pelanggaran')->get();
        $kelas = Santri::query()->select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $ringkas = [
            'jumlah' => $rekap->query($filter)->count(),
            'poin' => (int) $rekap->query($filter)->sum('poin_pelanggaran'),
            'santri' => $rekap->query($filter)->distinct('id_santri')->count('id_santri'),
        ];
        $batas = PoinService::batas();

        return view('laporan.index', compact('data', 'chart', 'kategori', 'kelas', 'filter', 'ringkas', 'batas'));
    }

    public function catatCetak()
    {
        return response()->noContent();
    }
}
