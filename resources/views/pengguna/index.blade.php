@extends('layouts.app')

@section('judul', 'Pengguna')
@section('subjudul', 'Akun admin, keamanan, lurah, dan pengasuh')

@section('aksi')
    <a class="btn-hijau" href="{{ route('pengguna.create') }}"><i class="bi bi-plus-lg"></i> Tambah pengguna</a>
@endsection

@section('content')
    <form class="kartu kartu-isi filter mb-3" method="GET">
        <input class="form-control" name="q" value="{{ $kata }}" placeholder="Nama atau username">
        <select class="form-select" name="role">
            <option value="">Semua role</option>
            @foreach(['admin' => 'Admin', 'keamanan' => 'Keamanan', 'lurah' => 'Lurah Pondok', 'pengasuh' => 'Pengasuh Pondok'] as $nilai => $label)
                <option value="{{ $nilai }}" @selected(request('role') === $nilai)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn-hijau" type="submit">Cari</button>
    </form>
    <div class="kartu">
        <div class="table-responsive">
            <table class="table tabel-santri">
                <thead><tr><th class="kolom-no">No</th><th>Pengguna</th><th>Role</th><th>Status</th><th>Terakhir masuk</th><th></th></tr></thead>
                <tbody>
                @forelse($data as $item)
                    <tr>
                        <td class="kolom-no">{{ $data->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="orang">
                                <span class="inisial kecil">{{ strtoupper(mb_substr($item->nama_user, 0, 1)) }}</span>
                                <span>
                                    <strong>{{ $item->nama_user }}</strong>
                                    <small>{{ $item->username }}</small>
                                </span>
                            </div>
                        </td>
                        <td>{{ $item->namaRole() }}</td>
                        <td><span class="lencana {{ $item->status }}">{{ ucfirst($item->status) }}</span></td>
                        <td>{{ $item->terakhir_masuk ? \Illuminate\Support\Carbon::parse($item->terakhir_masuk)->translatedFormat('d M Y H:i') : 'Belum pernah' }}</td>
                        <td class="aksi-baris">
                            <a class="btn-sm-aksi" href="{{ route('pengguna.edit', $item) }}">Ubah</a>
                            <form method="POST" action="{{ route('pengguna.destroy', $item) }}">
                                @csrf @method('DELETE')
                                <button class="btn-sm-aksi bahaya" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="kosong">Belum ada pengguna.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pager">{{ $data->links() }}</div>
    </div>
@endsection
