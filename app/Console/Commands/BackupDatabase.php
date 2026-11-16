<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'simonsa:backup {jenis=manual : otomatis atau manual}';

    protected $description = 'Membuat backup database SIMONSA';

    public function handle(BackupService $backup): int
    {
        $jenis = $this->argument('jenis') === 'otomatis' ? 'otomatis' : 'manual';
        $log = $backup->buat($jenis);

        if (! $log || $log->status_backup === 'gagal') {
            $this->error($log->keterangan ?? 'Backup gagal.');

            return self::FAILURE;
        }

        $this->info('Backup berhasil: '.$log->nama_file);

        return self::SUCCESS;
    }
}
