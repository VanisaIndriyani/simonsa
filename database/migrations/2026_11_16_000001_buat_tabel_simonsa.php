<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('nama_user', 100);
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->enum('role', ['admin', 'keamanan', 'lurah', 'pengasuh']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->dateTime('created_at')->nullable();
        });

        Schema::create('santri', function (Blueprint $table) {
            $table->increments('id_santri');
            $table->string('nis', 20)->unique();
            $table->string('nama_santri', 100);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat')->nullable();
            $table->string('kamar', 50)->nullable();
            $table->string('kelas', 30)->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'alumni'])->default('aktif');
        });

        Schema::create('kategori_pelanggaran', function (Blueprint $table) {
            $table->increments('id_kategori');
            $table->string('kode_pelanggaran', 20)->unique();
            $table->string('nama_pelanggaran', 150);
            $table->text('deskripsi')->nullable();
            $table->integer('poin');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->dateTime('created_at')->nullable();
        });

        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->increments('id_pelanggaran');
            $table->unsignedInteger('id_santri');
            $table->unsignedInteger('id_kategori');
            $table->unsignedInteger('id_user');
            $table->date('tanggal_pelanggaran');
            $table->text('kronologi');
            $table->integer('poin_pelanggaran');
            $table->text('tindak_lanjut')->nullable();
            $table->dateTime('created_at')->nullable();

            $table->foreign('id_santri')->references('id_santri')->on('santri')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_pelanggaran')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('id_user')->references('id_user')->on('pengguna')->restrictOnDelete()->cascadeOnUpdate();
        });

        Schema::create('surat_peringatan', function (Blueprint $table) {
            $table->increments('id_surat');
            $table->unsignedInteger('id_santri');
            $table->unsignedInteger('id_user');
            $table->string('nomor_surat', 50)->unique();
            $table->date('tanggal_surat');
            $table->integer('total_poin');
            $table->string('tingkat_peringatan', 30);
            $table->text('isi_surat');
            $table->dateTime('created_at')->nullable();

            $table->foreign('id_santri')->references('id_santri')->on('santri')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('id_user')->references('id_user')->on('pengguna')->restrictOnDelete()->cascadeOnUpdate();
        });

        Schema::create('tazir', function (Blueprint $table) {
            $table->increments('id_tazir');
            $table->unsignedInteger('id_pelanggaran');
            $table->unsignedInteger('id_user');
            $table->string('jenis_tazir', 150);
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->enum('status_pelaksanaan', ['menunggu', 'berjalan', 'selesai'])->default('menunggu');
            $table->text('keterangan')->nullable();
            $table->dateTime('created_at')->nullable();

            $table->foreign('id_pelanggaran')->references('id_pelanggaran')->on('pelanggaran')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('id_user')->references('id_user')->on('pengguna')->restrictOnDelete()->cascadeOnUpdate();
        });

        Schema::create('notifikasi', function (Blueprint $table) {
            $table->increments('id_notifikasi');
            $table->unsignedInteger('id_santri');
            $table->unsignedInteger('id_user');
            $table->string('judul', 100);
            $table->text('pesan');
            $table->string('jenis_notifikasi', 30);
            $table->enum('status_baca', ['belum', 'sudah'])->default('belum');
            $table->dateTime('created_at')->nullable();

            $table->foreign('id_santri')->references('id_santri')->on('santri')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('id_user')->references('id_user')->on('pengguna')->restrictOnDelete()->cascadeOnUpdate();
        });

        Schema::create('log_backup', function (Blueprint $table) {
            $table->increments('id_backup');
            $table->unsignedInteger('id_user');
            $table->string('nama_file', 150);
            $table->unsignedBigInteger('ukuran_file')->default(0);
            $table->enum('jenis_backup', ['otomatis', 'manual']);
            $table->enum('status_backup', ['berhasil', 'gagal']);
            $table->dateTime('waktu_backup');
            $table->text('keterangan')->nullable();

            $table->foreign('id_user')->references('id_user')->on('pengguna')->restrictOnDelete()->cascadeOnUpdate();
        });

        Schema::create('pengaturan_sistem', function (Blueprint $table) {
            $table->increments('id_pengaturan');
            $table->string('nama_pengaturan', 100)->unique();
            $table->string('nilai_pengaturan', 255);
            $table->text('keterangan')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistem');
        Schema::dropIfExists('log_backup');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('tazir');
        Schema::dropIfExists('surat_peringatan');
        Schema::dropIfExists('pelanggaran');
        Schema::dropIfExists('kategori_pelanggaran');
        Schema::dropIfExists('santri');
        Schema::dropIfExists('pengguna');
    }
};
