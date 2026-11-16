<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Support\Validasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $kata = $request->string('q')->toString();
        $data = Pengguna::query()
            ->when($kata, function ($query) use ($kata) {
                $query->where(function ($w) use ($kata) {
                    $w->where('nama_user', 'like', "%{$kata}%")
                        ->orWhere('username', 'like', "%{$kata}%");
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->role))
            ->withMax(['aktivitasLogin as terakhir_masuk' => fn ($query) => $query->where('jenis', 'masuk')], 'waktu')
            ->orderBy('nama_user')
            ->paginate(10)
            ->withQueryString();

        return view('pengguna.index', compact('data', 'kata'));
    }

    public function create()
    {
        return view('pengguna.form');
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        Pengguna::create($data);

        return redirect()->route('pengguna.index')->with('sukses', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(Pengguna $pengguna)
    {
        return view('pengguna.form', compact('pengguna'));
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $data = $this->validasi($request, $pengguna);
        if ($pengguna->role === 'admin' && ($data['role'] !== 'admin' || $data['status'] !== 'aktif')) {
            if ($this->adminAktifTersisa($pengguna) < 1) {
                return back()->withInput()->with('gagal', 'Harus tersisa minimal satu admin aktif.');
            }
        }
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $pengguna->update($data);

        return redirect()->route('pengguna.index')->with('sukses', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Pengguna $pengguna)
    {
        if ($pengguna->id_user === auth()->id()) {
            return back()->with('gagal', 'Anda tidak dapat menghapus akun yang sedang dipakai.');
        }
        if ($pengguna->role === 'admin' && $this->adminAktifTersisa($pengguna) < 1) {
            return back()->with('gagal', 'Admin terakhir tidak dapat dihapus.');
        }
        if ($pengguna->pelanggaran()->exists() || $pengguna->suratPeringatan()->exists() || $pengguna->tazir()->exists() || $pengguna->notifikasi()->exists() || $pengguna->logBackup()->exists()) {
            return back()->with('gagal', 'Pengguna masih memiliki data terkait. Nonaktifkan akun jika tidak lagi bertugas.');
        }
        $pengguna->delete();

        return back()->with('sukses', 'Pengguna berhasil dihapus.');
    }

    private function validasi(Request $request, ?Pengguna $pengguna = null): array
    {
        $wajibPassword = $pengguna ? 'nullable' : 'required';

        return $request->validate([
            'nama_user' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('pengguna', 'username')->ignore($pengguna?->id_user, 'id_user')],
            'password' => [$wajibPassword, 'string', 'min:6', 'max:255', 'confirmed'],
            'role' => ['required', 'in:admin,keamanan,lurah,pengasuh'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], Validasi::pesan(), [
            'nama_user' => 'nama',
            'username' => 'username',
            'password' => 'password',
            'role' => 'role',
            'status' => 'status',
        ]);
    }

    private function adminAktifTersisa(Pengguna $pengguna): int
    {
        return Pengguna::where('role', 'admin')
            ->where('status', 'aktif')
            ->where('id_user', '!=', $pengguna->id_user)
            ->count();
    }
}
