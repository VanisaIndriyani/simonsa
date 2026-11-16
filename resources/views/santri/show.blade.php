@extends('layouts.app')

@section('judul', 'Detail Santri')
@section('subjudul', $santri->nama_santri)

@section('aksi')
    <a class="btn-putih" href="{{ route('santri.index') }}">Kembali</a>
    @if(auth()->user()->admin())
        <a class="btn-putih" href="{{ route('santri.edit', $santri) }}">Ubah</a>
    @endif
    @if(auth()->user()->petugas())
        <a class="btn-hijau" href="{{ route('pelanggaran.create') }}">Catat pelanggaran</a>
    @endif
@endsection

@section('content')
    @php
        $progres = \App\Services\PoinService::progres($santri->poinTerkumpul());
        $inisial = strtoupper(mb_substr($santri->nama_santri, 0, 1));
    @endphp

    <section class="profil">
        <div class="kartu profil-utama">
            <div class="profil-kepala">
                <div class="inisial">{{ $inisial }}</div>
                <div>
                    <h2>{{ $santri->nama_santri }}</h2>
                    <div class="chip-bar">
                        <span class="chip">NIS {{ $santri->nis }}</span>
                        <span class="chip">{{ $santri->kelas ?: 'Kelas belum diisi' }}</span>
                        <span class="chip">Kamar {{ $santri->kamar ?: '-' }}</span>
                        <span class="lencana {{ $santri->status }}">{{ ucfirst($santri->status) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="kartu profil-poin">
            <div class="d-flex justify-content-between align-items-center">
                <span>Akumulasi poin</span>
                @include('partials.tingkat', ['tingkat' => $santri->tingkatSaatIni()])
            </div>
            <div class="angka">{{ $santri->poinTerkumpul() }}</div>
            <div class="bar"><span style="width: {{ $progres['persen'] }}%"></span></div>
            <small>{{ $progres['label'] }} · target {{ $progres['target'] }} poin</small>
        </div>
    </section>

    <section class="info-grid">
        <div class="kartu info-item">
            <span>Jenis kelamin</span>
            <strong>{{ $santri->jenis_kelamin }}</strong>
        </div>
        <div class="kartu info-item">
            <span>Tanggal masuk</span>
            <strong>{{ $santri->tanggal_masuk?->translatedFormat('d F Y') ?: '-' }}</strong>
        </div>
        <div class="kartu info-item">
            <span>Alamat</span>
            <strong>{{ $santri->alamat ?: '-' }}</strong>
        </div>
    </section>

    <div class="kartu mb-3">
        <div class="judul-bagian">
            <strong><i class="bi bi-journal-x"></i> Riwayat pelanggaran</strong>
            <span class="text-secondary">{{ $pelanggaran->count() }} catatan</span>
        </div>
        <div class="kartu-isi daftar-riwayat">
            @forelse($pelanggaran as $item)
                <article class="riwayat-item">
                    <div class="riwayat-no">{{ $loop->iteration }}</div>
                    <div>
                        <div class="riwayat-atas">
                            <a href="{{ route('pelanggaran.show', $item) }}">{{ $item->kategori->nama_pelanggaran }}</a>
                            <b class="poin-angka">{{ $item->poin_pelanggaran }}</b>
                        </div>
                        <p>{{ $item->kronologi }}</p>
                        <small>{{ $item->tanggal_pelanggaran->translatedFormat('d M Y') }} · {{ $item->pengguna->nama_user }}</small>
                    </div>
                </article>
            @empty
                <div class="kosong">Belum ada pelanggaran.</div>
            @endforelse
        </div>
    </div>

    @if(auth()->user()->petugas())
        <div class="kartu">
            <div class="judul-bagian">
                <strong><i class="bi bi-envelope-paper"></i> Surat peringatan</strong>
                <span class="text-secondary">{{ $surat->count() }} surat</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th class="kolom-no">No</th><th>Nomor</th><th>Tanggal</th><th>Tingkat</th><th>Poin</th><th></th></tr></thead>
                    <tbody>
                    @forelse($surat as $item)
                        <tr>
                            <td class="kolom-no">{{ $loop->iteration }}</td>
                            <td>{{ $item->nomor_surat }}</td>
                            <td>{{ $item->tanggal_surat->translatedFormat('d M Y') }}</td>
                            <td>@include('partials.tingkat', ['tingkat' => $item->tingkat_peringatan])</td>
                            <td><b class="poin-angka">{{ $item->total_poin }}</b></td>
                            <td><a class="btn-sm-aksi" href="{{ route('surat.cetak', $item) }}" target="_blank">Cetak</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="kosong">Belum ada surat peringatan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
