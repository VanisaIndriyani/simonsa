<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->increments('id_log');
            $table->unsignedInteger('id_user')->nullable();
            $table->string('nama_user', 100);
            $table->string('jenis', 20);
            $table->string('aktivitas', 255);
            $table->dateTime('waktu');

            $table->foreign('id_user')->references('id_user')->on('pengguna')->nullOnDelete()->cascadeOnUpdate();
        });

        if (Schema::hasTable('aktivitas_login')) {
            foreach (DB::table('aktivitas_login')->orderBy('id_aktivitas')->get() as $baris) {
                $kalimat = match ($baris->jenis) {
                    'masuk' => 'Masuk ke sistem',
                    'keluar' => 'Keluar dari sistem',
                    default => 'Gagal masuk ke sistem',
                };
                DB::table('log_aktivitas')->insert([
                    'id_user' => $baris->id_user,
                    'nama_user' => $baris->nama_user ?: $baris->username,
                    'jenis' => $baris->jenis,
                    'aktivitas' => $kalimat,
                    'waktu' => $baris->waktu,
                ]);
            }
        }

        $sekarang = now();
        foreach ([1, 2, 3, 4] as $urut) {
            $username = 'keamanan'.$urut;
            if (DB::table('pengguna')->where('username', $username)->exists()) {
                continue;
            }
            DB::table('pengguna')->insert([
                'nama_user' => 'Keamanan '.$urut,
                'username' => $username,
                'password' => Hash::make('keamanan123'),
                'role' => 'keamanan',
                'status' => 'aktif',
                'created_at' => $sekarang,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
        DB::table('pengguna')->whereIn('username', ['keamanan1', 'keamanan2', 'keamanan3', 'keamanan4'])->delete();
    }
};
