<?php

namespace App\Models;

use App\Models\Concerns\MencatatWaktu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tazir extends Model
{
    use MencatatWaktu;

    protected $table = 'tazir';

    protected $primaryKey = 'id_tazir';

    public $timestamps = false;

    protected $fillable = [
        'id_pelanggaran',
        'id_user',
        'jenis_tazir',
        'tanggal_pelaksanaan',
        'status_pelaksanaan',
        'keterangan',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pelaksanaan' => 'date',
            'created_at' => 'datetime',
        ];
    }

    public function pelanggaran(): BelongsTo
    {
        return $this->belongsTo(Pelanggaran::class, 'id_pelanggaran', 'id_pelanggaran');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_user');
    }

    public function labelStatus(): string
    {
        return match ($this->status_pelaksanaan) {
            'menunggu' => 'Menunggu',
            'berjalan' => 'Berjalan',
            'selesai' => 'Selesai',
            default => $this->status_pelaksanaan,
        };
    }
}
