@extends('layouts.app')

@section('judul', 'Log Aktivitas')
@section('subjudul', 'Riwayat masuk, menu, dan perubahan data')

@section('content')
    <form class="kartu kartu-isi filter mb-3" method="GET">
        <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Nama atau aktivitas">
        <select class="form-select" name="jenis">
            <option value="">Semua aktivitas</option>
            @foreach(['masuk' => 'Masuk', 'keluar' => 'Keluar', 'gagal' => 'Gagal masuk', 'lihat' => 'Melihat menu', 'tambah' => 'Tambah', 'ubah' => 'Ubah', 'hapus' => 'Hapus', 'cetak' => 'Cetak'] as $nilai => $label)
                <option value="{{ $nilai }}" @selected(request('jenis') === $nilai)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn-hijau" type="submit">Cari</button>
    </form>
    <div class="kartu">
        <div class="table-responsive">
            <table class="table tabel-santri">
                <thead><tr><th class="kolom-no">No</th><th>Tanggal</th><th>Jam</th><th>Pengguna</th><th>Aktivitas</th></tr></thead>
                <tbody>
                @forelse($data as $item)
                    <tr>
                        <td class="kolom-no">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $item->waktu->translatedFormat('d M Y') }}</td>
                        <td>{{ $item->waktu->format('H:i') }}</td>
                        <td>
                            <div class="orang">
                                <span class="inisial kecil">{{ strtoupper(mb_substr($item->nama_user, 0, 1)) }}</span>
                                <span>
                                    <strong>{{ $item->nama_user }}</strong>
                                    <small>{{ $item->labelJenis() }}</small>
                                </span>
                            </div>
                        </td>
                        <td>{{ $item->aktivitas }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="kosong">Belum ada aktivitas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pager">{{ $data->links() }}</div>
    </div>
@endsection
