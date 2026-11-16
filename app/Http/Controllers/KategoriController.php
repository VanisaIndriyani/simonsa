<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelanggaran;
use App\Support\Validasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $kata = $request->string('q')->toString();
        $data = KategoriPelanggaran::query()
            ->when($kata, function ($query) use ($kata) {
                $query->where(function ($w) use ($kata) {
                    $w->where('nama_pelanggaran', 'like', "%{$kata}%")
                        ->orWhere('kode_pelanggaran', 'like', "%{$kata}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->orderByDesc('poin')
            ->paginate(10)
            ->withQueryString();

        return view('kategori.index', compact('data', 'kata'));
    }

    public function create()
    {
        return view('kategori.form');
    }

    public function store(Request $request)
    {
        KategoriPelanggaran::create($this->validasi($request));

        return redirect()->route('kategori.index')->with('sukses', 'Kategori pelanggaran berhasil disimpan.');
    }

    public function edit(KategoriPelanggaran $kategori)
    {
        return view('kategori.form', compact('kategori'));
    }

    public function update(Request $request, KategoriPelanggaran $kategori)
    {
        $kategori->update($this->validasi($request, $kategori));

        return redirect()->route('kategori.index')->with('sukses', 'Kategori pelanggaran berhasil diperbarui.');
    }

    public function destroy(KategoriPelanggaran $kategori)
    {
        if ($kategori->pelanggaran()->exists()) {
            return back()->with('gagal', 'Kategori masih dipakai pada data pelanggaran.');
        }
        $kategori->delete();

        return back()->with('sukses', 'Kategori berhasil dihapus.');
    }

    private function validasi(Request $request, ?KategoriPelanggaran $kategori = null): array
    {
        return $request->validate([
            'kode_pelanggaran' => ['required', 'string', 'max:20', Rule::unique('kategori_pelanggaran', 'kode_pelanggaran')->ignore($kategori?->id_kategori, 'id_kategori')],
            'nama_pelanggaran' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'poin' => ['required', 'integer', 'min:1', 'max:1000'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], Validasi::pesan(), [
            'kode_pelanggaran' => 'kode',
            'nama_pelanggaran' => 'nama pelanggaran',
            'deskripsi' => 'deskripsi',
            'poin' => 'poin',
            'status' => 'status',
        ]);
    }
}
