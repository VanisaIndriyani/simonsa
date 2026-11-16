@extends('layouts.app')

@section('judul', 'Aktivitas Login')
@section('subjudul', 'Riwayat masuk dan keluar setiap akun')

@section('content')
    <form class="kartu kartu-isi filter mb-3" method="GET">
        <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Nama atau username">
        <select class="form-select" name="role">
            <option value="">Semua role</option>
            @foreach(['admin' => 'Admin', 'keamanan' => 'Keamanan', 'lurah' => 'Lurah Pondok', 'pengasuh' => 'Pengasuh Pondok'] as $nilai => $label)
                <option value="{{ $nilai }}" @selected(request('role') === $nilai)>{{ $label }}</option>
            @endforeach
        </select>
        <select class="form-select" name="jenis">
            <option value="">Semua kegiatan</option>
            @foreach(['masuk' => 'Masuk', 'keluar' => 'Keluar', 'gagal' => 'Gagal masuk'] as $nilai => $label)
                <option value="{{ $nilai }}" @selected(request('jenis') === $nilai)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn-hijau" type="submit">Cari</button>
    </form>
    <div class="kartu">
        <div class="table-responsive">
            <table class="table tabel-santri">
                <thead><tr><th class="kolom-no">No</th><th>Waktu</th><th>Pengguna</th><th>Role</th><th>Kegiatan</th><th>Alamat IP</th></tr></thead>
                <tbody>
                @forelse($data as $item)
                    <tr>
                        <td class="kolom-no">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $item->waktu->translatedFormat('d M Y H:i') }}</td>
                        <td>
                            <div class="orang">
                                <span class="inisial kecil">{{ strtoupper(mb_substr($item->nama_user ?: $item->username, 0, 1)) }}</span>
                                <span>
                                    <strong>{{ $item->nama_user ?: 'Akun tidak dikenal' }}</strong>
                                    <small>{{ $item->username }}</small>
                                </span>
                            </div>
                        </td>
                        <td>{{ $item->namaRole() }}</td>
                        <td><span class="lencana {{ $item->jenis }}">{{ $item->labelJenis() }}</span></td>
                        <td>{{ $item->alamat_ip ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="kosong">Belum ada aktivitas login.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pager">{{ $data->links() }}</div>
    </div>
@endsection
