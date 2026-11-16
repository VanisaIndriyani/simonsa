<?php

namespace App\Http\Controllers;

use App\Models\PengaturanSistem;
use App\Services\PoinService;
use App\Support\Validasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengaturanController extends Controller
{
    public function index()
    {
        $nilai = [
            'nama_pondok' => PengaturanSistem::ambil('nama_pondok', 'Pondok Pesantren Al Huda Kebumen'),
            'nama_sistem' => PengaturanSistem::ambil('nama_sistem', 'SIMONSA'),
            'kota_surat' => PengaturanSistem::ambil('kota_surat', 'Kebumen'),
            'batas_sp1' => PengaturanSistem::ambil('batas_sp1', '25'),
            'batas_sp2' => PengaturanSistem::ambil('batas_sp2', '50'),
            'batas_sp3' => PengaturanSistem::ambil('batas_sp3', '75'),
            'jadwal_backup' => PengaturanSistem::ambil('jadwal_backup', '00:00'),
        ];

        return view('pengaturan.index', compact('nilai'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama_pondok' => ['required', 'string', 'max:255'],
            'nama_sistem' => ['required', 'string', 'max:255'],
            'kota_surat' => ['required', 'string', 'max:255'],
            'batas_sp1' => ['required', 'integer', 'min:1', 'max:9999'],
            'batas_sp2' => ['required', 'integer', 'min:1', 'max:9999'],
            'batas_sp3' => ['required', 'integer', 'min:1', 'max:9999'],
            'jadwal_backup' => ['required', 'date_format:H:i'],
        ], Validasi::pesan(), [
            'nama_pondok' => 'nama pondok',
            'nama_sistem' => 'nama sistem',
            'kota_surat' => 'kota surat',
            'batas_sp1' => 'batas SP1',
            'batas_sp2' => 'batas SP2',
            'batas_sp3' => 'batas SP3',
            'jadwal_backup' => 'jadwal backup',
        ]);

        if (! ($data['batas_sp1'] < $data['batas_sp2'] && $data['batas_sp2'] < $data['batas_sp3'])) {
            return back()->withInput()->with('gagal', 'Batas poin harus naik: SP1 di bawah SP2, SP2 di bawah SP3.');
        }

        $keterangan = [
            'nama_pondok' => 'Nama pondok yang tampil di sistem dan surat.',
            'nama_sistem' => 'Nama aplikasi.',
            'kota_surat' => 'Kota pada tanggal surat peringatan.',
            'batas_sp1' => 'Akumulasi poin minimum untuk Surat Peringatan 1.',
            'batas_sp2' => 'Akumulasi poin minimum untuk Surat Peringatan 2.',
            'batas_sp3' => 'Akumulasi poin minimum untuk Surat Peringatan 3.',
            'jadwal_backup' => 'Jam backup otomatis setiap hari (WIB).',
        ];

        foreach ($data as $nama => $isi) {
            PengaturanSistem::simpan($nama, (string) $isi, $keterangan[$nama]);
        }
        PoinService::lupakanBatas();

        return back()->with('sukses', 'Pengaturan sistem berhasil disimpan.');
    }

    public function profil(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'nama_user' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('pengguna', 'username')->ignore($user->id_user, 'id_user')],
            'password' => ['nullable', 'string', 'min:6', 'max:255', 'confirmed'],
        ], Validasi::pesan(), [
            'nama_user' => 'nama',
            'username' => 'username',
            'password' => 'password',
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }
        $user->update($data);

        return back()->with('sukses', 'Profil berhasil diperbarui.');
    }
}
