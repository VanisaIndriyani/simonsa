<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\SuratPeringatan;
use App\Services\PoinService;
use App\Support\Validasi;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $kata = $request->string('q')->toString();
        $data = SuratPeringatan::with(['santri', 'pengguna'])
            ->when($kata, function ($query) use ($kata) {
                $query->where('nomor_surat', 'like', "%{$kata}%")
                    ->orWhereHas('santri', fn ($santri) => $santri->where('nama_santri', 'like', "%{$kata}%")->orWhere('nis', 'like', "%{$kata}%"));
            })
            ->when($request->filled('tingkat'), fn ($query) => $query->where('tingkat_peringatan', $request->tingkat))
            ->orderByDesc('tanggal_surat')
            ->paginate(10)
            ->withQueryString();

        return view('surat.index', compact('data', 'kata'));
    }

    public function create(Request $request)
    {
        $santri = Santri::orderBy('nama_santri')->denganPoin()->get();
        $pilihan = $request->integer('santri');
        $tingkat = $request->string('tingkat')->toString();
        if (! in_array($tingkat, ['SP1', 'SP2', 'SP3'], true)) {
            $tingkat = '';
        }

        return view('surat.form', compact('santri', 'pilihan', 'tingkat'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['id_user'] = $request->user()->id_user;
        if (blank($data['nomor_surat'] ?? null)) {
            $data['nomor_surat'] = SuratPeringatan::buatNomor($data['tingkat_peringatan'], $data['tanggal_surat']);
        }
        $surat = SuratPeringatan::create($data);

        return redirect()->route('surat.show', $surat)->with('sukses', 'Surat peringatan berhasil diterbitkan.');
    }

    public function show(SuratPeringatan $surat)
    {
        $surat->load(['santri.pelanggaran.kategori', 'pengguna']);

        return view('surat.show', compact('surat'));
    }

    public function edit(SuratPeringatan $surat)
    {
        $santri = Santri::orderBy('nama_santri')->denganPoin()->get();

        return view('surat.form', compact('surat', 'santri'));
    }

    public function update(Request $request, SuratPeringatan $surat)
    {
        $surat->update($this->validasi($request, $surat));

        return redirect()->route('surat.show', $surat)->with('sukses', 'Surat peringatan berhasil diperbarui.');
    }

    public function cetak(SuratPeringatan $surat)
    {
        $surat->load(['santri.pelanggaran.kategori', 'pengguna']);
        $namaPondok = \App\Models\PengaturanSistem::ambil('nama_pondok', 'Pondok Pesantren Al Huda Kebumen');
        $kota = \App\Models\PengaturanSistem::ambil('kota_surat', 'Kebumen');

        return view('surat.cetak', compact('surat', 'namaPondok', 'kota'));
    }

    public function destroy(SuratPeringatan $surat)
    {
        $surat->delete();

        return redirect()->route('surat.index')->with('sukses', 'Surat peringatan berhasil dihapus.');
    }

    private function validasi(Request $request, ?SuratPeringatan $surat = null): array
    {
        $santri = Santri::find($request->input('id_santri'));
        $tingkat = (string) $request->input('tingkat_peringatan');
        $poin = $santri ? PoinService::total($santri->id_santri) : 0;

        $data = $request->validate([
            'id_santri' => ['required', 'integer', 'exists:santri,id_santri'],
            'nomor_surat' => ['nullable', 'string', 'max:50', \Illuminate\Validation\Rule::unique('surat_peringatan', 'nomor_surat')->ignore($surat?->id_surat, 'id_surat')],
            'tanggal_surat' => ['required', 'date', 'before_or_equal:today'],
            'tingkat_peringatan' => ['required', 'in:SP1,SP2,SP3'],
            'total_poin' => ['nullable', 'integer', 'min:0'],
            'isi_surat' => ['nullable', 'string'],
        ], Validasi::pesan(), [
            'id_santri' => 'santri',
            'nomor_surat' => 'nomor surat',
            'tanggal_surat' => 'tanggal surat',
            'tingkat_peringatan' => 'tingkat peringatan',
            'total_poin' => 'total poin',
            'isi_surat' => 'isi surat',
        ]);

        if (! isset($data['total_poin']) || $data['total_poin'] === null || $data['total_poin'] === '') {
            $data['total_poin'] = $poin;
        }
        if (blank($data['isi_surat'] ?? null) && $santri) {
            $data['isi_surat'] = SuratPeringatan::templateIsi($santri, $tingkat, (int) $data['total_poin']);
        }

        return $data;
    }
}
