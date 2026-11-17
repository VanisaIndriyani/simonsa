<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $primaryKey = 'id_log';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_user',
        'jenis',
        'aktivitas',
        'waktu',
    ];

    protected function casts(): array
    {
        return [
            'waktu' => 'datetime',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_user');
    }

    public static function catat(?Pengguna $pengguna, string $jenis, string $aktivitas, ?string $nama = null): void
    {
        if (! Schema::hasTable('log_aktivitas')) {
            return;
        }

        static::create([
            'id_user' => $pengguna?->id_user,
            'nama_user' => $nama ?: ($pengguna?->nama_user ?: 'Tidak dikenal'),
            'jenis' => $jenis,
            'aktivitas' => $aktivitas,
            'waktu' => now(),
        ]);
    }

    public function labelJenis(): string
    {
        return match ($this->jenis) {
            'masuk' => 'Masuk',
            'keluar' => 'Keluar',
            'gagal' => 'Gagal masuk',
            'lihat' => 'Melihat',
            'tambah' => 'Tambah',
            'ubah' => 'Ubah',
            'hapus' => 'Hapus',
            'cetak' => 'Cetak',
            default => ucfirst($this->jenis),
        };
    }
}
