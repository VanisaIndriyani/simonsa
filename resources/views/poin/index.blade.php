@extends('layouts.app')

@section('judul', 'Akumulasi Poin')
@section('subjudul')
SP1 {{ $batas['sp1'] }} poin, SP2 {{ $batas['sp2'] }} poin, SP3 {{ $batas['sp3'] }} poin
@endsection

@section('content')
    <form class="kartu kartu-isi filter mb-3" method="GET">
        <input class="form-control" name="q" value="{{ $kata }}" placeholder="Nama atau NIS">
        <select class="form-select" name="kelas">
            <option value="">Semua kelas</option>
            @foreach($kelas as $item)
                <option value="{{ $item }}" @selected(request('kelas') === $item)>{{ $item }}</option>
            @endforeach
        </select>
        <button class="btn-hijau" type="submit">Cari</button>
    </form>
    <div class="kartu">
        <div class="table-responsive">
            <table class="table tabel-santri">
                <thead><tr><th class="kolom-no">No</th><th>Santri</th><th>Kelas</th><th>Poin</th><th>Tingkat</th><th>Progres</th></tr></thead>
                <tbody>
                @forelse($data as $santri)
                    @php
                        $progres = \App\Services\PoinService::progres($santri->poinTerkumpul());
                        $tingkat = $santri->tingkatSaatIni();
                        $warna = $tingkat ? strtolower($tingkat) : 'aman';
                    @endphp
                    <tr>
                        <td class="kolom-no">{{ $data->firstItem() + $loop->index }}</td>
                        <td>
                            <a class="orang" href="{{ route('santri.show', $santri) }}">
                                <span class="inisial kecil">{{ strtoupper(mb_substr($santri->nama_santri, 0, 1)) }}</span>
                                <span>
                                    <strong>{{ $santri->nama_santri }}</strong>
                                    <small>NIS {{ $santri->nis }}</small>
                                </span>
                            </a>
                        </td>
                        <td>{{ $santri->kelas ?: '-' }}</td>
                        <td><b class="poin-angka {{ $warna }}">{{ $santri->poinTerkumpul() }}</b></td>
                        <td>@include('partials.tingkat', ['tingkat' => $tingkat])</td>
                        <td style="min-width:180px">
                            <div class="bar {{ $warna }}"><span style="width: {{ $progres['persen'] }}%"></span></div>
                            <small class="text-secondary">{{ $progres['label'] }}</small>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="kosong">Belum ada data poin.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pager">{{ $data->links() }}</div>
    </div>
@endsection
