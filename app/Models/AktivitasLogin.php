<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitasLogin extends Model
{
    protected $table = 'aktivitas_login';

    protected $primaryKey = 'id_aktivitas';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_user',
        'username',
        'role',
        'jenis',
        'alamat_ip',
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

    public static function catat(?Pengguna $pengguna, string $jenis, ?string $username, ?string $ip): void
    {
        static::create([
            'id_user' => $pengguna?->id_user,
            'nama_user' => $pengguna?->nama_user,
            'username' => $pengguna->username ?? ($username ?: '-'),
            'role' => $pengguna?->role,
            'jenis' => $jenis,
            'alamat_ip' => $ip,
            'waktu' => now(),
        ]);

        $kalimat = match ($jenis) {
            'masuk' => 'Masuk ke sistem',
            'keluar' => 'Keluar dari sistem',
            default => 'Gagal masuk ke sistem',
        };
        LogAktivitas::catat($pengguna, $jenis, $kalimat, $pengguna?->nama_user ?: $username);
    }

    public function namaRole(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'keamanan' => 'Keamanan',
            'lurah' => 'Lurah Pondok',
            'pengasuh' => 'Pengasuh Pondok',
            default => '-',
        };
    }

    public function labelJenis(): string
    {
        return match ($this->jenis) {
            'masuk' => 'Masuk',
            'keluar' => 'Keluar',
            'gagal' => 'Gagal masuk',
            default => ucfirst($this->jenis),
        };
    }
}
