<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Services\PoinService;
use Illuminate\Http\Request;

class PoinController extends Controller
{
    public function index(Request $request)
    {
        $kata = $request->string('q')->toString();
        $data = Santri::query()
            ->denganPoin()
            ->when($kata, function ($query) use ($kata) {
                $query->where(function ($w) use ($kata) {
                    $w->where('nama_santri', 'like', "%{$kata}%")
                        ->orWhere('nis', 'like', "%{$kata}%");
                });
            })
            ->when($request->filled('kelas'), fn ($query) => $query->where('kelas', $request->kelas))
            ->orderByDesc('pelanggaran_sum_poin_pelanggaran')
            ->orderBy('nama_santri')
            ->paginate(12)
            ->withQueryString();

        $kelas = Santri::query()->select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $batas = PoinService::batas();

        return view('poin.index', compact('data', 'kelas', 'kata', 'batas'));
    }
}
