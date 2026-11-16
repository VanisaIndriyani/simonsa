<?php

namespace App\Providers;

use App\Models\Notifikasi;
use App\Models\PengaturanSistem;
use App\Support\Simonsa;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Carbon::setLocale(config('app.locale'));

        View::composer(['layouts.app', 'layouts.guest', 'auth.login', 'surat.cetak', 'laporan.index'], function ($view) {
            $view->with('logoUrl', Simonsa::logo());
            $nama = 'Pondok Pesantren Al Huda Kebumen';
            try {
                if (Schema::hasTable('pengaturan_sistem')) {
                    $nama = PengaturanSistem::ambil('nama_pondok', $nama) ?: $nama;
                }
            } catch (Throwable $e) {
            }
            $view->with('namaPondok', $nama);
        });

        View::composer('layouts.app', function ($view) {
            $jumlah = 0;
            $user = auth()->user();
            try {
                if ($user && $user->petugas() && Schema::hasTable('notifikasi')) {
                    $query = Notifikasi::where('status_baca', 'belum');
                    if (! $user->admin()) {
                        $query->where('id_user', $user->id_user);
                    }
                    $jumlah = $query->count();
                }
            } catch (Throwable $e) {
            }
            $view->with('jumlahNotif', $jumlah);
        });
    }
}
