@extends('layouts.app')

@section('judul', 'Pengaturan Sistem')
@section('subjudul', 'Profil akun, batas surat peringatan, dan jadwal backup')

@section('content')
    @php $saya = auth()->user(); @endphp
    <div class="form-layout mb-3">
        <form class="kartu kartu-isi formulir" method="POST" action="{{ route('pengaturan.profil') }}">
            @csrf
            @method('PUT')
            <div class="bagian-form">
                <h3>Profil saya</h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="nama_user">Nama</label>
                        <input class="form-control" id="nama_user" name="nama_user" value="{{ old('nama_user', $saya->nama_user) }}" required maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-control" id="username" name="username" value="{{ old('username', $saya->username) }}" required maxlength="50">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password">Password baru</label>
                        <div class="sandi">
                            <input class="form-control" id="password" type="password" name="password" minlength="6" placeholder="Kosongkan jika tidak diubah">
                            <button class="mata" type="button" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password_confirmation">Ulangi password</label>
                        <div class="sandi">
                            <input class="form-control" id="password_confirmation" type="password" name="password_confirmation">
                            <button class="mata" type="button" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-aksi">
                <button class="btn-hijau" type="submit">Simpan profil</button>
            </div>
        </form>
        <aside class="kartu pratinjau">
            <div class="inisial">{{ strtoupper(mb_substr($saya->nama_user, 0, 1)) }}</div>
            <strong>{{ $saya->nama_user }}</strong>
            <div class="chip-bar justify-content-center">
                <span class="chip">{{ $saya->username }}</span>
                <span class="chip">{{ $saya->namaRole() }}</span>
            </div>
            <p>Role dan status akun diubah dari menu Pengguna.</p>
        </aside>
    </div>

    <form class="kartu kartu-isi formulir" method="POST" action="{{ route('pengaturan.update') }}">
        @csrf
        @method('PUT')
        <div class="bagian-form">
            <h3>Identitas pondok</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="nama_pondok">Nama pondok</label>
                    <input class="form-control" id="nama_pondok" name="nama_pondok" value="{{ old('nama_pondok', $nilai['nama_pondok']) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="nama_sistem">Nama sistem</label>
                    <input class="form-control" id="nama_sistem" name="nama_sistem" value="{{ old('nama_sistem', $nilai['nama_sistem']) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="kota_surat">Kota surat</label>
                    <input class="form-control" id="kota_surat" name="kota_surat" value="{{ old('kota_surat', $nilai['kota_surat']) }}" required>
                </div>
            </div>
        </div>
        <div class="bagian-form">
            <h3>Batas dan backup</h3>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label" for="batas_sp1">Batas SP1</label>
                    <input class="form-control" id="batas_sp1" type="number" name="batas_sp1" value="{{ old('batas_sp1', $nilai['batas_sp1']) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="batas_sp2">Batas SP2</label>
                    <input class="form-control" id="batas_sp2" type="number" name="batas_sp2" value="{{ old('batas_sp2', $nilai['batas_sp2']) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="batas_sp3">Batas SP3</label>
                    <input class="form-control" id="batas_sp3" type="number" name="batas_sp3" value="{{ old('batas_sp3', $nilai['batas_sp3']) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="jadwal_backup">Jadwal backup (WIB)</label>
                    <input class="form-control" id="jadwal_backup" type="time" name="jadwal_backup" value="{{ old('jadwal_backup', $nilai['jadwal_backup']) }}" required>
                </div>
            </div>
            <div class="catatan mt-3">Backup otomatis berjalan setiap hari pada jam di atas. Keputusan sanksi tetap di luar sistem.</div>
        </div>
        <div class="form-aksi">
            <button class="btn-hijau" type="submit">Simpan pengaturan</button>
        </div>
    </form>
@endsection
