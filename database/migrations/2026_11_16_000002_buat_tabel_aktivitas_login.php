<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aktivitas_login', function (Blueprint $table) {
            $table->increments('id_aktivitas');
            $table->unsignedInteger('id_user')->nullable();
            $table->string('nama_user', 100)->nullable();
            $table->string('username', 50);
            $table->string('role', 20)->nullable();
            $table->enum('jenis', ['masuk', 'keluar', 'gagal']);
            $table->string('alamat_ip', 45)->nullable();
            $table->dateTime('waktu');

            $table->foreign('id_user')->references('id_user')->on('pengguna')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktivitas_login');
    }
};
