<?php

namespace App\Http\Middleware;

use App\Models\LogAktivitas;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CatatAktivitas
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = $request->user();
        $nama = $request->route()?->getName();
        if (! $user || ! $nama || in_array($nama, ['logout', 'login'], true) || $response->getStatusCode() >= 400) {
            return $response;
        }

        [$jenis, $teks] = $this->kalimat($request, $nama);
        if ($jenis) {
            LogAktivitas::catat($user, $jenis, $teks);
        }

        return $response;
    }

    private function kalimat(Request $request, string $nama): array
    {
        $halaman = $this->halaman($nama);

        if (str_contains($nama, 'cetak')) {
            $teks = str_contains($nama, 'laporan') ? 'Mencetak laporan' : 'Mencetak surat peringatan';

            return ['cetak', $teks];
        }

        if (str_ends_with($nama, '.store')) {
            return ['tambah', 'Menambah data '.$halaman];
        }

        if (str_ends_with($nama, '.update') || str_ends_with($nama, '.profil') || str_ends_with($nama, '.baca') || str_ends_with($nama, '.baca-semua') || str_ends_with($nama, '.pulihkan')) {
            return ['ubah', 'Mengubah data '.$halaman];
        }

        if (str_ends_with($nama, '.destroy')) {
            return ['hapus', 'Menghapus data '.$halaman];
        }

        if ($request->isMethod('GET')) {
            return ['lihat', 'Membuka menu '.$halaman];
        }

        return [null, null];
    }

    private function halaman(string $route): string
    {
        return match (strtok($route, '.')) {
            'dashboard' => 'Dashboard',
            'santri' => 'Data Santri',
            'kategori' => 'Kategori Pelanggaran',
            'pelanggaran' => 'Data Pelanggaran',
            'riwayat' => 'Riwayat Pelanggaran',
            'poin' => 'Akumulasi Poin',
            'laporan' => 'Laporan dan Grafik',
            'notifikasi' => 'Notifikasi',
            'surat' => 'Surat Peringatan',
            'tazir' => "Ta'zir",
            'pengguna' => 'Pengguna',
            'aktivitas' => 'Log Aktivitas',
            'pengaturan' => 'Pengaturan Sistem',
            'backup' => 'Backup Database',
            default => $route,
        };
    }
}
