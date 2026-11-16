@extends('layouts.app')

@section('judul', 'Surat Peringatan')
@section('subjudul', 'SP1, SP2, dan SP3 yang diterbitkan petugas')

@section('aksi')
    <a class="btn-hijau" href="{{ route('surat.create') }}"><i class="bi bi-plus-lg"></i> Terbitkan surat</a>
@endsection

@section('content')
    <form class="kartu kartu-isi filter mb-3 no-print" method="GET">
        <input class="form-control" name="q" value="{{ $kata }}" placeholder="Nomor, nama, atau NIS">
        <select class="form-select" name="tingkat">
            <option value="">Semua tingkat</option>
            @foreach(['SP1', 'SP2', 'SP3'] as $tingkat)
                <option value="{{ $tingkat }}" @selected(request('tingkat') === $tingkat)>{{ $tingkat }}</option>
            @endforeach
        </select>
        <button class="btn-hijau" type="submit">Cari</button>
    </form>
    <div class="kartu">
        <div class="table-responsive">
            <table class="table tabel-santri">
                <thead><tr><th class="kolom-no">No</th><th>Surat</th><th>Tanggal</th><th>Tingkat</th><th>Poin</th><th></th></tr></thead>
                <tbody>
                @forelse($data as $item)
                    @php $warna = strtolower($item->tingkat_peringatan); @endphp
                    <tr>
                        <td class="kolom-no">{{ $data->firstItem() + $loop->index }}</td>
                        <td>
                            <a class="orang" href="{{ route('surat.show', $item) }}">
                                <span class="inisial kecil">{{ strtoupper(mb_substr($item->santri->nama_santri, 0, 1)) }}</span>
                                <span>
                                    <strong>{{ $item->santri->nama_santri }}</strong>
                                    <small>{{ $item->nomor_surat }}</small>
                                </span>
                            </a>
                        </td>
                        <td>{{ $item->tanggal_surat->translatedFormat('d M Y') }}</td>
                        <td>@include('partials.tingkat', ['tingkat' => $item->tingkat_peringatan])</td>
                        <td><b class="poin-angka {{ $warna }}">{{ $item->total_poin }}</b></td>
                        <td class="aksi-baris">
                            <a class="btn-sm-aksi" href="{{ route('surat.show', $item) }}">Detail</a>
                            <a class="btn-sm-aksi" href="{{ route('surat.cetak', $item) }}" target="_blank">Cetak</a>
                            <a class="btn-sm-aksi" href="{{ route('surat.edit', $item) }}">Ubah</a>
                            @if(auth()->user()->admin())
                                <form method="POST" action="{{ route('surat.destroy', $item) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn-sm-aksi bahaya" type="submit">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="kosong">Belum ada surat peringatan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pager">{{ $data->links() }}</div>
    </div>
@endsection
