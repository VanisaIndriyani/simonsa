<?php

namespace Database\Seeders;

use App\Models\KategoriPelanggaran;
use App\Models\Notifikasi;
use App\Models\Pelanggaran;
use App\Models\PengaturanSistem;
use App\Models\Pengguna;
use App\Models\Santri;
use App\Models\SuratPeringatan;
use App\Models\Tazir;
use App\Services\PoinService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $pengaturan = [
            ['nama_pondok', 'Pondok Pesantren Al Huda Kebumen', 'Nama pondok yang tampil di sistem dan surat.'],
            ['nama_sistem', 'SIMONSA', 'Nama aplikasi.'],
            ['kota_surat', 'Kebumen', 'Kota pada tanggal surat peringatan.'],
            ['batas_sp1', '25', 'Akumulasi poin minimum untuk Surat Peringatan 1.'],
            ['batas_sp2', '50', 'Akumulasi poin minimum untuk Surat Peringatan 2.'],
            ['batas_sp3', '75', 'Akumulasi poin minimum untuk Surat Peringatan 3.'],
            ['jadwal_backup', '00:00', 'Jam backup otomatis setiap hari (WIB).'],
        ];
        foreach ($pengaturan as [$nama, $nilai, $ket]) {
            PengaturanSistem::simpan($nama, $nilai, $ket);
        }

        Pengguna::create([
            'nama_user' => 'Ust. Hadi Wijaya',
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin',
            'status' => 'aktif',
        ]);
        $keamanan = Pengguna::create([
            'nama_user' => 'Ust. Ridwan Setiawan',
            'username' => 'keamanan',
            'password' => 'keamanan123',
            'role' => 'keamanan',
            'status' => 'aktif',
        ]);
        Pengguna::create([
            'nama_user' => 'Muhammad Luthfi',
            'username' => 'lurah',
            'password' => 'lurah123',
            'role' => 'lurah',
            'status' => 'aktif',
        ]);
        Pengguna::create([
            'nama_user' => 'KH. Abdul Huda',
            'username' => 'pengasuh',
            'password' => 'pengasuh123',
            'role' => 'pengasuh',
            'status' => 'aktif',
        ]);

        $daftarSantri = [
            ['2023001', 'Ahmad Fauzi', 'Laki-laki', 'Karangsari, Kebumen', 'A1', '10 MA', '2023-07-15'],
            ['2023002', 'Muhammad Rizki', 'Laki-laki', 'Pejagoan, Kebumen', 'A1', '11 MA', '2023-07-15'],
            ['2023008', 'Abdul Hakim', 'Laki-laki', 'Alian, Kebumen', 'A2', '12 MA', '2023-07-16'],
            ['2024003', 'Fadlan Ramadhan', 'Laki-laki', 'Kutowinangun, Kebumen', 'B1', '8 MTs', '2024-07-14'],
            ['2024007', 'Yusuf Maulana', 'Laki-laki', 'Prembun, Kebumen', 'B1', '9 MTs', '2024-07-14'],
            ['2024011', 'Ilham Pratama', 'Laki-laki', 'Klirong, Kebumen', 'B2', '7 MTs', '2024-07-15'],
            ['2023015', 'Salman Alfarizi', 'Laki-laki', 'Ambal, Kebumen', 'A2', '11 MA', '2023-07-16'],
            ['2024019', 'Hafidz Nurrohman', 'Laki-laki', 'Sruweng, Kebumen', 'C1', '8 MTs', '2024-07-15'],
            ['2023022', 'Zaki Mubarok', 'Laki-laki', 'Buluspesantren, Kebumen', 'C1', '10 MA', '2023-07-17'],
            ['2024025', 'Farhan Abdullah', 'Laki-laki', 'Adimulyo, Kebumen', 'A1', '9 MTs', '2024-07-16'],
            ['2025028', 'Nabil Khairul', 'Laki-laki', 'Rowokele, Kebumen', 'B2', '7 MTs', '2025-07-13'],
            ['2023030', 'Reza Aditya', 'Laki-laki', 'Gombong, Kebumen', 'C1', '12 MA', '2023-07-17'],
        ];
        foreach ($daftarSantri as $baris) {
            Santri::create([
                'nis' => $baris[0],
                'nama_santri' => $baris[1],
                'jenis_kelamin' => $baris[2],
                'alamat' => $baris[3],
                'kamar' => $baris[4],
                'kelas' => $baris[5],
                'tanggal_masuk' => $baris[6],
                'status' => 'aktif',
            ]);
        }

        $daftarKategori = [
            ['TLMB', 'Terlambat shalat berjamaah', 'Tidak hadir di shaf saat iqamah.', 5],
            ['KJRN', 'Tidak mengikuti kajian', 'Tidak hadir pada kajian wajib tanpa izin.', 10],
            ['PKET', 'Tidak piket', 'Tidak menjalankan jadwal piket kamar atau masjid.', 5],
            ['BHSA', 'Tidak berbahasa resmi', 'Berbicara di luar bahasa resmi pondok pada jam wajib.', 5],
            ['MBLS', 'Membolos madrasah', 'Tidak mengikuti pelajaran madrasah tanpa keterangan.', 15],
            ['IZIN', 'Keluar pondok tanpa izin', 'Meninggalkan area pondok tanpa surat izin.', 20],
            ['HAPE', 'Membawa HP', 'Menyimpan atau menggunakan telepon genggam.', 25],
            ['RKOK', 'Merokok', 'Merokok di dalam atau di luar lingkungan pondok.', 30],
            ['KLHI', 'Berkelahi', 'Terlibat perkelahian dengan santri lain.', 40],
            ['PCRN', 'Berpacaran', 'Menjalin hubungan pacaran.', 35],
        ];
        foreach ($daftarKategori as $baris) {
            KategoriPelanggaran::create([
                'kode_pelanggaran' => $baris[0],
                'nama_pelanggaran' => $baris[1],
                'deskripsi' => $baris[2],
                'poin' => $baris[3],
                'status' => 'aktif',
                'created_at' => Carbon::parse('2026-06-01 07:00:00'),
            ]);
        }

        $santri = Santri::pluck('id_santri', 'nis');
        $kategori = KategoriPelanggaran::pluck('id_kategori', 'kode_pelanggaran');

        $catatan = [
            ['2024019', 'KJRN', '2026-06-18', 'Tidak hadir kajian malam tanpa keterangan.', 'Ditegur dan diminta menyalin materi kajian.'],
            ['2023022', 'TLMB', '2026-07-07', 'Tiba di masjid setelah rakaat pertama shalat Maghrib.', 'Menulis istighfar 100 kali.'],
            ['2023001', 'RKOK', '2026-08-14', 'Ditemukan merokok di belakang kamar A1.', 'HP saku diperiksa dan diminta membersihkan masjid.'],
            ['2023008', 'KJRN', '2026-08-20', 'Tidak mengikuti kajian kitab ba\'da Isya.', 'Menyalin catatan kajian.'],
            ['2024025', 'PKET', '2026-08-25', 'Tidak melaksanakan piket kamar pada pagi hari.', 'Mengganti piket di hari berikutnya.'],
            ['2023002', 'KLHI', '2026-09-02', 'Terlibat dorong-mendorong dengan santri kamar sebelah.', 'Didamaikan dan diminta saling memaafkan.'],
            ['2023008', 'TLMB', '2026-09-05', 'Terlambat shalat Subuh berjamaah.', 'Membaca Al-Qur\'an satu halaman.'],
            ['2023015', 'PKET', '2026-09-16', 'Meninggalkan jadwal piket masjid.', 'Mengganti piket sore.'],
            ['2023001', 'HAPE', '2026-09-20', 'Menyimpan HP di dalam lemari kamar.', 'HP ditahan bagian keamanan.'],
            ['2023008', 'PKET', '2026-10-02', 'Tidak piket kamar saat giliran minggu ini.', 'Membersihkan kamar bersama.'],
            ['2023002', 'MBLS', '2026-10-11', 'Tidak masuk pelajaran fiqih tanpa izin.', 'Menyalin materi pelajaran.'],
            ['2025028', 'BHSA', '2026-10-19', 'Berbicara bahasa daerah pada jam bahasa resmi.', 'Menghafal kosakata bahasa Arab.'],
            ['2023001', 'IZIN', '2026-10-28', 'Keluar ke warung tanpa surat izin keamanan.', 'Dicatat dan dilaporkan ke lurah kamar.'],
            ['2023008', 'BHSA', '2026-11-01', 'Tidak memakai bahasa resmi di area madrasah.', 'Membuat pernyataan tertulis.'],
            ['2024003', 'MBLS', '2026-11-04', 'Tidak mengikuti pelajaran nahwu.', 'Menyalin catatan pelajaran.'],
            ['2024007', 'PKET', '2026-11-08', 'Tidak piket halaman pagi.', 'Mengganti piket sore.'],
            ['2023015', 'KJRN', '2026-11-09', 'Tidak hadir pada kajian tafsir.', 'Menyalin ringkasan kajian.'],
            ['2024011', 'BHSA', '2026-11-10', 'Berbicara di luar bahasa resmi di depan kamar.', 'Ditegur wali kamar.'],
            ['2024003', 'TLMB', '2026-11-12', 'Terlambat shalat Ashar berjamaah.', 'Menulis istighfar.'],
            ['2023030', 'MBLS', '2026-11-15', 'Tidak masuk madrasah pada jam pertama.', 'Menyusun kembali jadwal belajar.'],
        ];

        foreach ($catatan as $baris) {
            PoinService::catat([
                'id_santri' => $santri[$baris[0]],
                'id_kategori' => $kategori[$baris[1]],
                'tanggal_pelanggaran' => $baris[2],
                'kronologi' => $baris[3],
                'tindak_lanjut' => $baris[4],
            ], $keamanan, Carbon::parse($baris[2])->setTime(8, 30));
        }

        $surat = [
            ['2023001', '001/SP1/SIMONSA/VIII/2026', '2026-08-15', 30, 'SP1'],
            ['2023002', '004/SP1/SIMONSA/IX/2026', '2026-09-03', 40, 'SP1'],
            ['2023001', '002/SP2/SIMONSA/IX/2026', '2026-09-21', 55, 'SP2'],
            ['2023002', '005/SP2/SIMONSA/X/2026', '2026-10-12', 55, 'SP2'],
            ['2023001', '003/SP3/SIMONSA/X/2026', '2026-10-29', 75, 'SP3'],
            ['2023008', '006/SP1/SIMONSA/XI/2026', '2026-11-02', 25, 'SP1'],
        ];
        foreach ($surat as $baris) {
            $anak = Santri::find($santri[$baris[0]]);
            SuratPeringatan::create([
                'id_santri' => $anak->id_santri,
                'id_user' => $keamanan->id_user,
                'nomor_surat' => $baris[1],
                'tanggal_surat' => $baris[2],
                'total_poin' => $baris[3],
                'tingkat_peringatan' => $baris[4],
                'isi_surat' => SuratPeringatan::templateIsi($anak, $baris[4], $baris[3]),
                'created_at' => Carbon::parse($baris[2])->setTime(9, 0),
            ]);
        }

        $cari = function (string $nis, string $kode) use ($santri, $kategori) {
            return Pelanggaran::where('id_santri', $santri[$nis])->where('id_kategori', $kategori[$kode])->first();
        };

        $tazir = [
            [$cari('2023001', 'RKOK'), 'Membersihkan area masjid', '2026-08-16', 'selesai', 'Selesai dikerjakan setelah Ashar.'],
            [$cari('2023001', 'HAPE'), 'HP ditahan bagian keamanan', '2026-09-21', 'berjalan', 'HP masih ditahan hingga masa pembinaan selesai.'],
            [$cari('2023002', 'KLHI'), 'Meminta maaf dan setor hafalan', '2026-09-04', 'menunggu', 'Hafalan belum disetorkan kepada musyrif.'],
            [$cari('2023008', 'BHSA'), 'Menulis pernyataan tertulis', '2026-11-03', 'selesai', 'Pernyataan sudah diterima keamanan.'],
        ];
        foreach ($tazir as $baris) {
            Tazir::create([
                'id_pelanggaran' => $baris[0]->id_pelanggaran,
                'id_user' => $keamanan->id_user,
                'jenis_tazir' => $baris[1],
                'tanggal_pelaksanaan' => $baris[2],
                'status_pelaksanaan' => $baris[3],
                'keterangan' => $baris[4],
                'created_at' => Carbon::parse($baris[2])->setTime(10, 0),
            ]);
        }

        Notifikasi::whereDate('created_at', '<', '2026-10-01')->update(['status_baca' => 'sudah']);
    }
}
