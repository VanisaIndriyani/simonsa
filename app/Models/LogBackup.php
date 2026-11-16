<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogBackup extends Model
{
    protected $table = 'log_backup';

    protected $primaryKey = 'id_backup';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_file',
        'ukuran_file',
        'jenis_backup',
        'status_backup',
        'waktu_backup',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'ukuran_file' => 'integer',
            'waktu_backup' => 'datetime',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_user');
    }

    public function ukuranLabel(): string
    {
        $byte = (int) $this->ukuran_file;
        if ($byte < 1024) {
            return $byte.' B';
        }
        if ($byte < 1048576) {
            return number_format($byte / 1024, 1, ',', '.').' KB';
        }

        return number_format($byte / 1048576, 2, ',', '.').' MB';
    }
}
