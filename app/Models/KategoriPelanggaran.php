<?php

namespace App\Models;

use App\Models\Concerns\MencatatWaktu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriPelanggaran extends Model
{
    use MencatatWaktu;

    protected $table = 'kategori_pelanggaran';

    protected $primaryKey = 'id_kategori';

    public $timestamps = false;

    protected $fillable = [
        'kode_pelanggaran',
        'nama_pelanggaran',
        'deskripsi',
        'poin',
        'status',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'poin' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function pelanggaran(): HasMany
    {
        return $this->hasMany(Pelanggaran::class, 'id_kategori', 'id_kategori');
    }
}
