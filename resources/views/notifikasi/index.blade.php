@extends('layouts.app')

@section('judul', 'Notifikasi')
@section('subjudul', auth()->user()->admin() ? 'Semua notifikasi batas poin' : 'Notifikasi untuk akun Anda')

@section('aksi')
    <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
        @csrf
        <button class="btn-putih" type="submit">Tandai semua dibaca</button>
    </form>
@endsection

@section('content')
    <form class="kartu kartu-isi filter mb-3" method="GET">
        <select class="form-select" name="status" onchange="this.form.submit()">
            <option value="">Semua</option>
            <option value="belum" @selected(request('status') === 'belum')>Belum dibaca</option>
            <option value="sudah" @selected(request('status') === 'sudah')>Sudah dibaca</option>
        </select>
    </form>

    <div class="kartu">
        <div class="kartu-isi daftar-riwayat">
            @forelse($data as $item)
                <article class="riwayat-item {{ $item->status_baca === 'belum' ? 'notif-baru' : '' }}">
                    <div class="riwayat-no">{{ $data->firstItem() + $loop->index }}</div>
                    <div>
                        <div class="riwayat-atas">
                            <strong>{{ $item->judul }}</strong>
                            <span class="lencana {{ $item->status_baca }}">{{ $item->status_baca === 'belum' ? 'Belum dibaca' : 'Sudah dibaca' }}</span>
                        </div>
                        <a class="orang riwayat-orang" href="{{ route('santri.show', $item->santri) }}">
                            <span class="inisial kecil">{{ strtoupper(mb_substr($item->santri->nama_santri, 0, 1)) }}</span>
                            <span>
                                <strong>{{ $item->santri->nama_santri }}</strong>
                                <small>{{ $item->santri->nis }} · {{ $item->santri->kelas }}</small>
                            </span>
                        </a>
                        <p>{{ $item->pesan }}</p>
                        <small>
                            {{ $item->created_at?->translatedFormat('d M Y H:i') }}
                            @if(auth()->user()->admin()) · untuk {{ $item->pengguna->nama_user }} @endif
                        </small>
                        @if($item->status_baca === 'belum')
                            <div class="aksi-baris mt-2">
                                <form method="POST" action="{{ route('notifikasi.baca', $item) }}">
                                    @csrf
                                    <button class="btn-sm-aksi" type="submit">Tandai dibaca</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <div class="kosong">Tidak ada notifikasi.</div>
            @endforelse
        </div>
        <div class="pager">{{ $data->links() }}</div>
    </div>
@endsection
