@extends('layouts.app')

@section('judul', 'Riwayat Pelanggaran')
@section('subjudul', 'Urutan kejadian dari yang paling baru')

@section('content')
    <form class="kartu kartu-isi filter mb-3 no-print" method="GET">
        <input class="form-control" name="q" value="{{ $filter['q'] ?? '' }}" placeholder="Nama atau NIS">
        <select class="form-select" name="kelas">
            <option value="">Semua kelas</option>
            @foreach($kelas as $item)
                <option value="{{ $item }}" @selected(($filter['kelas'] ?? '') === $item)>{{ $item }}</option>
            @endforeach
        </select>
        <button class="btn-hijau" type="submit">Saring</button>
    </form>

    <div class="kartu">
        <div class="kartu-isi daftar-riwayat">
            @forelse($data as $item)
                @php $warna = $item->poin_pelanggaran >= 25 ? 'sp3' : ($item->poin_pelanggaran >= 10 ? 'sp1' : 'aman'); @endphp
                <article class="riwayat-item">
                    <div class="riwayat-no">{{ $data->firstItem() + $loop->index }}</div>
                    <div>
                        <div class="riwayat-atas">
                            <a href="{{ route('pelanggaran.show', $item) }}">{{ $item->kategori->nama_pelanggaran }}</a>
                            <b class="poin-angka {{ $warna }}">{{ $item->poin_pelanggaran }}</b>
                        </div>
                        <a class="orang riwayat-orang" href="{{ route('santri.show', $item->santri) }}">
                            <span class="inisial kecil">{{ strtoupper(mb_substr($item->santri->nama_santri, 0, 1)) }}</span>
                            <span>
                                <strong>{{ $item->santri->nama_santri }}</strong>
                                <small>{{ $item->santri->nis }} · {{ $item->santri->kelas }} · kamar {{ $item->santri->kamar ?: '-' }}</small>
                            </span>
                        </a>
                        <p>{{ $item->kronologi }}</p>
                        <small>{{ $item->tanggal_pelanggaran->translatedFormat('d M Y') }} · {{ $item->pengguna->nama_user }}</small>
                        @if($item->tindak_lanjut)<div class="catatan mt-2">Tindak lanjut: {{ $item->tindak_lanjut }}</div>@endif
                    </div>
                </article>
            @empty
                <div class="kosong">Belum ada riwayat.</div>
            @endforelse
        </div>
        <div class="pager">{{ $data->links() }}</div>
    </div>
@endsection
