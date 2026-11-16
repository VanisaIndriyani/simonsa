<?php

namespace App\Models;

use App\Services\PoinService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Santri extends Model
{
    protected $table = 'santri';

    protected $primaryKey = 'id_santri';

    public $timestamps = false;

    protected $fillable = [
        'nis',
        'nama_santri',
        'jenis_kelamin',
        'alamat',
        'kamar',
        'kelas',
        'tanggal_masuk',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'date',
        ];
    }

    public function scopeDenganPoin($query)
    {
        return $query->withSum('pelanggaran', 'poin_pelanggaran');
    }

    public function poinTerkumpul(): int
    {
        if (array_key_exists('pelanggaran_sum_poin_pelanggaran', $this->attributes)) {
            return (int) $this->attributes['pelanggaran_sum_poin_pelanggaran'];
        }

        return (int) $this->pelanggaran()->sum('poin_pelanggaran');
    }

    public function tingkatSaatIni(): ?string
    {
        return PoinService::tingkat($this->poinTerkumpul());
    }

    public function pelanggaran(): HasMany
    {
        return $this->hasMany(Pelanggaran::class, 'id_santri', 'id_santri');
    }

    public function suratPeringatan(): HasMany
    {
        return $this->hasMany(SuratPeringatan::class, 'id_santri', 'id_santri');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_santri', 'id_santri');
    }
}
