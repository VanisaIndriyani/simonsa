<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSistem extends Model
{
    protected $table = 'pengaturan_sistem';

    protected $primaryKey = 'id_pengaturan';

    public $timestamps = false;

    protected $fillable = [
        'nama_pengaturan',
        'nilai_pengaturan',
        'keterangan',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'updated_at' => 'datetime',
        ];
    }

    public static function ambil(string $nama, ?string $bawaan = null): ?string
    {
        $baris = static::query()->where('nama_pengaturan', $nama)->first();

        return $baris->nilai_pengaturan ?? $bawaan;
    }

    public static function simpan(string $nama, string $nilai, ?string $keterangan = null): void
    {
        $baris = static::query()->firstOrNew(['nama_pengaturan' => $nama]);
        $baris->nilai_pengaturan = $nilai;
        if ($keterangan !== null) {
            $baris->keterangan = $keterangan;
        }
        $baris->updated_at = now();
        $baris->save();
    }
}
