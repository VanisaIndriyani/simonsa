<?php

namespace App\Services;

use App\Models\LogBackup;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class BackupService
{
    public function otomatisJikaPerlu(): void
    {
        if (! Schema::hasTable('log_backup') || ! Schema::hasTable('pengaturan_sistem') || ! Schema::hasTable('pengguna')) {
            return;
        }

        $jam = \App\Models\PengaturanSistem::ambil('jadwal_backup', '00:00') ?: '00:00';
        if (! preg_match('/^\d{2}:\d{2}$/', $jam)) {
            $jam = '00:00';
        }
        if (now()->format('H:i') < $jam) {
            return;
        }

        $kunci = 'simonsa.backup.'.now()->toDateString();
        if (Cache::has($kunci)) {
            return;
        }

        $sudah = LogBackup::where('jenis_backup', 'otomatis')
            ->whereDate('waktu_backup', now()->toDateString())
            ->where('status_backup', 'berhasil')
            ->exists();

        if ($sudah) {
            Cache::put($kunci, 1, now()->endOfDay());

            return;
        }

        $log = $this->buat('otomatis');
        if ($log && $log->status_backup === 'berhasil') {
            Cache::put($kunci, 1, now()->endOfDay());
        }
    }

    public function buat(string $jenis, ?int $idUser = null): ?LogBackup
    {
        $pelaksana = $idUser
            ? Pengguna::find($idUser)
            : Pengguna::where('role', 'admin')->orderBy('id_user')->first();

        if (! $pelaksana) {
            return null;
        }

        $nama = 'simonsa-'.now()->format('Ymd-His').'.sqlite';
        $keterangan = $jenis === 'otomatis'
            ? 'Backup otomatis harian.'
            : 'Backup manual oleh '.$pelaksana->nama_user.'.';

        try {
            $path = $this->salinSqlite($nama);
            $log = LogBackup::create([
                'id_user' => $pelaksana->id_user,
                'nama_file' => $nama,
                'ukuran_file' => filesize($path) ?: 0,
                'jenis_backup' => $jenis === 'otomatis' ? 'otomatis' : 'manual',
                'status_backup' => 'berhasil',
                'waktu_backup' => now(),
                'keterangan' => $keterangan,
            ]);
        } catch (Throwable $e) {
            $log = LogBackup::create([
                'id_user' => $pelaksana->id_user,
                'nama_file' => $nama,
                'ukuran_file' => 0,
                'jenis_backup' => $jenis === 'otomatis' ? 'otomatis' : 'manual',
                'status_backup' => 'gagal',
                'waktu_backup' => now(),
                'keterangan' => 'Backup gagal: '.$e->getMessage(),
            ]);
        }

        return $log;
    }

    public function pulihkan(string $sumber): void
    {
        $nama = 'simonsa-sebelum-pulih-'.now()->format('Ymd-His').'.sqlite';
        $this->salinSqlite($nama);
        $ukuran = filesize(storage_path('app/backups/'.$nama)) ?: 0;

        DB::disconnect('sqlite');
        DB::purge('sqlite');

        if (! @copy($sumber, $this->berkasSqlite())) {
            DB::reconnect('sqlite');
            throw new \RuntimeException('Berkas database sedang dipakai. Tutup program lain lalu coba lagi.');
        }

        DB::reconnect('sqlite');

        $admin = Pengguna::where('role', 'admin')->orderBy('id_user')->first();
        if ($admin) {
            LogBackup::create([
                'id_user' => $admin->id_user,
                'nama_file' => $nama,
                'ukuran_file' => $ukuran,
                'jenis_backup' => 'manual',
                'status_backup' => 'berhasil',
                'waktu_backup' => now(),
                'keterangan' => 'Salinan otomatis sebelum database dipulihkan.',
            ]);
        }
    }

    public function hapus(LogBackup $log): void
    {
        $path = storage_path('app/backups/'.basename((string) $log->nama_file));
        if (is_file($path)) {
            unlink($path);
        }
        $log->delete();
    }

    public function sql(): string
    {
        $tabel = [
            'pengguna',
            'santri',
            'kategori_pelanggaran',
            'pelanggaran',
            'surat_peringatan',
            'tazir',
            'notifikasi',
            'log_backup',
            'pengaturan_sistem',
        ];

        $sql = "-- SIMONSA\n-- Sistem Monitoring Pelanggaran Santri\n-- ".now()->format('Y-m-d H:i:s')." WIB\n\n";
        foreach ($tabel as $nama) {
            $baris = DB::table($nama)->get();
            $sql .= "-- {$nama} (".$baris->count()." baris)\n";
            foreach ($baris as $row) {
                $data = (array) $row;
                $kolom = array_keys($data);
                $nilai = array_map(function ($nilai) {
                    if ($nilai === null) {
                        return 'NULL';
                    }

                    return "'".str_replace("'", "''", (string) $nilai)."'";
                }, array_values($data));
                $sql .= 'INSERT INTO '.$nama.' ('.implode(', ', $kolom).') VALUES ('.implode(', ', $nilai).");\n";
            }
            $sql .= "\n";
        }

        return $sql;
    }

    private function salinSqlite(string $nama): string
    {
        $dir = storage_path('app/backups');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $tujuan = $dir.DIRECTORY_SEPARATOR.$nama;
        $kutip = DB::connection()->getPdo()->quote(str_replace('\\', '/', $tujuan));

        try {
            DB::statement('VACUUM INTO '.$kutip);
        } catch (Throwable $e) {
            if (! @copy($this->berkasSqlite(), $tujuan)) {
                throw $e;
            }
        }

        return $tujuan;
    }

    private function berkasSqlite(): string
    {
        $path = (string) config('database.connections.sqlite.database');
        if (! preg_match('/^(?:[A-Za-z]:[\\\\\\/]|\/)/', $path)) {
            $path = base_path($path);
        }

        return $path;
    }
}
