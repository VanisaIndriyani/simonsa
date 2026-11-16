<?php

namespace App\Models;

use App\Models\Concerns\MencatatWaktu;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use MencatatWaktu;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_user';

    public $timestamps = false;

    protected $fillable = [
        'nama_user',
        'username',
        'password',
        'role',
        'status',
        'created_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'created_at' => 'datetime',
        ];
    }

    public function admin(): bool
    {
        return $this->role === 'admin';
    }

    public function petugas(): bool
    {
        return in_array($this->role, ['admin', 'keamanan'], true);
    }

    public function namaRole(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'keamanan' => 'Keamanan',
            'lurah' => 'Lurah Pondok',
            'pengasuh' => 'Pengasuh Pondok',
            default => ucfirst($this->role),
        };
    }

    public function pelanggaran(): HasMany
    {
        return $this->hasMany(Pelanggaran::class, 'id_user', 'id_user');
    }

    public function suratPeringatan(): HasMany
    {
        return $this->hasMany(SuratPeringatan::class, 'id_user', 'id_user');
    }

    public function tazir(): HasMany
    {
        return $this->hasMany(Tazir::class, 'id_user', 'id_user');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_user', 'id_user');
    }

    public function logBackup(): HasMany
    {
        return $this->hasMany(LogBackup::class, 'id_user', 'id_user');
    }

    public function aktivitasLogin(): HasMany
    {
        return $this->hasMany(AktivitasLogin::class, 'id_user', 'id_user');
    }
}
