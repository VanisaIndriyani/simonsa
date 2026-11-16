@extends('layouts.app')

@section('judul', isset($pengguna) ? 'Ubah Pengguna' : 'Tambah Pengguna')
@section('subjudul', isset($pengguna) ? $pengguna->nama_user : 'Password disimpan dalam bentuk terenkripsi')

@section('content')
    @php $ubah = isset($pengguna); @endphp
    <div class="form-layout">
        <form class="kartu kartu-isi formulir" method="POST" action="{{ $ubah ? route('pengguna.update', $pengguna) : route('pengguna.store') }}">
            @csrf
            @if($ubah) @method('PUT') @endif
            <div class="bagian-form">
                <h3>Akun</h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="nama_user">Nama</label>
                        <input class="form-control" id="nama_user" name="nama_user" value="{{ old('nama_user', $pengguna->nama_user ?? '') }}" required maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-control" id="username" name="username" value="{{ old('username', $pengguna->username ?? '') }}" required maxlength="50">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password">Password {{ $ubah ? '(kosongkan jika tidak diubah)' : '' }}</label>
                        <div class="sandi">
                            <input class="form-control" id="password" type="password" name="password" {{ $ubah ? '' : 'required' }} minlength="6">
                            <button class="mata" type="button" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password_confirmation">Ulangi password</label>
                        <div class="sandi">
                            <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" {{ $ubah ? '' : 'required' }}>
                            <button class="mata" type="button" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bagian-form">
                <h3>Akses</h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="role">Role</label>
                        <select class="form-select" id="role" name="role">
                            @foreach(['admin' => 'Admin', 'keamanan' => 'Keamanan', 'lurah' => 'Lurah Pondok', 'pengasuh' => 'Pengasuh Pondok'] as $nilai => $label)
                                <option value="{{ $nilai }}" @selected(old('role', $pengguna->role ?? 'keamanan') === $nilai)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            @foreach(['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $nilai => $label)
                                <option value="{{ $nilai }}" @selected(old('status', $pengguna->status ?? 'aktif') === $nilai)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-aksi">
                <button class="btn-hijau" type="submit">Simpan</button>
                <a class="btn-putih" href="{{ route('pengguna.index') }}">Batal</a>
            </div>
        </form>
        <aside class="kartu pratinjau">
            <div class="inisial" id="pratinjau-inisial">{{ $ubah ? strtoupper(mb_substr($pengguna->nama_user, 0, 1)) : 'P' }}</div>
            <strong id="pratinjau-nama">{{ $ubah ? $pengguna->nama_user : 'Nama pengguna' }}</strong>
            <div class="chip-bar justify-content-center">
                <span class="chip" id="pratinjau-user">{{ $ubah ? $pengguna->username : 'username' }}</span>
                <span class="chip" id="pratinjau-role">Keamanan</span>
            </div>
        </aside>
    </div>
@endsection

@push('scripts')
<script>
    const isi = (id, teks) => { document.getElementById(id).textContent = teks; };
    const segar = () => {
        const nama = document.getElementById('nama_user').value.trim();
        const role = document.getElementById('role');
        isi('pratinjau-nama', nama || 'Nama pengguna');
        isi('pratinjau-inisial', (nama || 'P').charAt(0).toUpperCase());
        isi('pratinjau-user', document.getElementById('username').value.trim() || 'username');
        isi('pratinjau-role', role.selectedOptions[0].textContent);
    };
    ['nama_user', 'username', 'role'].forEach((id) => {
        document.getElementById(id).addEventListener('input', segar);
        document.getElementById(id).addEventListener('change', segar);
    });
    segar();
</script>
@endpush
