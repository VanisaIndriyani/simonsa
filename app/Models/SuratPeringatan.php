<?php

namespace App\Models;

use App\Models\Concerns\MencatatWaktu;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPeringatan extends Model
{
    use MencatatWaktu;

    protected $table = 'surat_peringatan';

    protected $primaryKey = 'id_surat';

    public $timestamps = false;

    protected $fillable = [
        'id_santri',
        'id_user',
        'nomor_surat',
        'tanggal_surat',
        'total_poin',
        'tingkat_peringatan',
        'isi_surat',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'total_poin' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_user');
    }

    public static function buatNomor(string $tingkat, $tanggal): string
    {
        $tgl = Carbon::parse($tanggal);
        $urut = static::whereYear('tanggal_surat', $tgl->year)->count() + 1;
        $romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];

        return sprintf('%03d/%s/SIMONSA/%s/%d', $urut, $tingkat, $romawi[(int) $tgl->format('n')], $tgl->year);
    }

    public static function templateIsi(Santri $santri, string $tingkat, int $poin): string
    {
        return "Berdasarkan catatan pelanggaran di SIMONSA, santri atas nama {$santri->nama_santri} (NIS {$santri->nis}), kelas {$santri->kelas}, kamar {$santri->kamar}, telah mencapai akumulasi {$poin} poin.\n\n"
            ."Dengan ini diterbitkan {$tingkat} sebagai peringatan resmi. Santri diminta menghentikan pelanggaran dan mengikuti pembinaan bagian keamanan.\n\n"
            .'Keputusan sanksi akhir tetap dilakukan di luar sistem oleh pengasuh pondok.';
    }
}
