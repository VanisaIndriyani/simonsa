<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;

$jam = '00:00';

try {
    if (Schema::hasTable('pengaturan_sistem')) {
        $nilai = \App\Models\PengaturanSistem::ambil('jadwal_backup', '00:00');
        if (is_string($nilai) && preg_match('/^\d{2}:\d{2}$/', $nilai)) {
            $jam = $nilai;
        }
    }
} catch (Throwable $e) {
    $jam = '00:00';
}

Schedule::command('simonsa:backup otomatis')
    ->dailyAt($jam)
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();
