@extends('layouts.app')

@section('judul', 'Data Pelanggaran')
@section('subjudul', 'Catatan pelanggaran yang masuk ke akumulasi poin')

@section('aksi')
    @if(auth()->user()->petugas())
        <a class="btn-hijau" href="{{ route('pelanggaran.create') }}"><i class="bi bi-plus-lg"></i> Catat pelanggaran</a>
    @endif
@endsection

@section('content')
    <form class="kartu kartu-isi filter mb-3 no-print" method="GET">
        <input class="form-control" name="q" value="{{ $filter['q'] ?? '' }}" placeholder="Nama, NIS, atau kronologi">
        <select class="form-select" name="id_kategori">
            <option value="">Semua kategori</option>
            @foreach($kategori as $item)
                <option value="{{ $item->id_kategori }}" @selected(($filter['id_kategori'] ?? '') == $item->id_kategori)>{{ $item->nama_pelanggaran }}</option>
            @endforeach
        </select>
        <select class="form-select" name="kelas">
            <option value="">Semua kelas</option>
            @foreach($kelas as $item)
                <option value="{{ $item }}" @selected(($filter['kelas'] ?? '') === $item)>{{ $item }}</option>
            @endforeach
        </select>
        <input class="form-control" type="date" name="dari" value="{{ $filter['dari'] ?? '' }}">
        <input class="form-control" type="date" name="sampai" value="{{ $filter['sampai'] ?? '' }}">
        <button class="btn-hijau" type="submit">Saring</button>
    </form>
    <div class="kartu">
        <div class="table-responsive">
            <table class="table tabel-santri">
                <thead><tr><th class="kolom-no">No</th><th>Tanggal</th><th>Santri</th><th>Kategori</th><th>Poin</th><th>Petugas</th><th></th></tr></thead>
                <tbody>
                @forelse($data as $item)
                    @php $warna = $item->poin_pelanggaran >= 25 ? 'sp3' : ($item->poin_pelanggaran >= 10 ? 'sp1' : 'aman'); @endphp
                    <tr>
                        <td class="kolom-no">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $item->tanggal_pelanggaran->translatedFormat('d M Y') }}</td>
                        <td>
                            <a class="orang" href="{{ route('santri.show', $item->santri) }}">
                                <span class="inisial kecil">{{ strtoupper(mb_substr($item->santri->nama_santri, 0, 1)) }}</span>
                                <span>
                                    <strong>{{ $item->santri->nama_santri }}</strong>
                                    <small>{{ $item->santri->nis }} · {{ $item->santri->kelas }}</small>
                                </span>
                            </a>
                        </td>
                        <td>{{ $item->kategori->nama_pelanggaran }}</td>
                        <td><b class="poin-angka {{ $warna }}">{{ $item->poin_pelanggaran }}</b></td>
                        <td>{{ $item->pengguna->nama_user }}</td>
                        <td class="aksi-baris">
                            <a class="btn-sm-aksi" href="{{ route('pelanggaran.show', $item) }}">Detail</a>
                            @if(auth()->user()->petugas())
                                <a class="btn-sm-aksi" href="{{ route('pelanggaran.edit', $item) }}">Ubah</a>
                            @endif
                            @if(auth()->user()->admin())
                                <form method="POST" action="{{ route('pelanggaran.destroy', $item) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn-sm-aksi bahaya" type="submit">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="kosong">Belum ada pelanggaran.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pager">{{ $data->links() }}</div>
    </div>
@endsection
