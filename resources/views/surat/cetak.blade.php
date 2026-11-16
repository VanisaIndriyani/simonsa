<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $surat->nomor_surat }}</title>
    <link rel="stylesheet" href="{{ asset('css/simonsa.css') }}">
    <style>
        body { background: #e7eee9; }
        .alat-cetak {
            width: min(820px, 100%);
            margin: 16px auto 0;
            display: flex;
            gap: 8px;
        }
        .kertas {
            width: min(820px, 100%);
            margin: 16px auto 32px;
            background: white;
            color: #1a1a1a;
            font-family: "Times New Roman", Times, serif;
            line-height: 1.55;
            padding: 40px 48px 36px;
            box-shadow: 0 16px 40px rgba(16, 20, 24, .08);
        }
        .kop-surat { display: flex; gap: 16px; align-items: center; }
        .kop-surat img { width: 72px; height: 72px; object-fit: cover; border-radius: 8px; }
        .kop-surat .lembaga { flex: 1; text-align: center; padding-right: 72px; }
        .kop-surat .merek { letter-spacing: .22em; font-size: 12px; color: #146c38; font-family: "Segoe UI", sans-serif; }
        .kop-surat h1 { margin: 2px 0; font-size: 22px; letter-spacing: .02em; }
        .kop-surat p { margin: 0; font-size: 14px; }
        .garis-kop { margin-top: 10px; border-top: 3px solid #146c38; border-bottom: 1px solid #1a1a1a; height: 5px; }
        .judul-surat { text-align: center; margin: 22px 0 0; font-size: 18px; letter-spacing: .04em; }
        .nomor-surat { text-align: center; margin: 4px 0 18px; }
        .tingkat-surat {
            display: inline-block;
            margin-top: 8px;
            border: 1.5px solid #1a1a1a;
            padding: 1px 12px;
            font-family: "Segoe UI", sans-serif;
            font-size: 13px;
            letter-spacing: .08em;
        }
        .tingkat-surat.sp1 { background: #fff3cc; }
        .tingkat-surat.sp2 { background: #ffe8d6; }
        .tingkat-surat.sp3 { background: #fde8e6; }
        .meta-surat { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .meta-surat td { padding: 3px 0; vertical-align: top; }
        .meta-surat td:first-child { width: 150px; }
        .meta-surat td:nth-child(2) { width: 16px; }
        .isi-surat { text-align: justify; margin: 0 0 18px; }
        .isi-surat p { margin: 0 0 12px; }
        .tabel-surat { width: 100%; border-collapse: collapse; font-size: 14px; }
        .tabel-surat th, .tabel-surat td { border: 1px solid #c5cfc8; padding: 7px 10px; }
        .tabel-surat th { background: #f3faf6; text-align: left; font-family: "Segoe UI", sans-serif; font-size: 12px; letter-spacing: .04em; }
        .tabel-surat .angka { text-align: right; width: 72px; }
        .tabel-surat .no { width: 42px; text-align: center; }
        .tabel-surat tfoot td { font-weight: 700; }
        .penutup { display: flex; justify-content: flex-end; margin-top: 28px; }
        .ttd { width: 230px; text-align: center; }
        .ttd .ruang { height: 76px; }
        .ttd strong { display: inline-block; min-width: 180px; border-top: 1px solid #1a1a1a; padding-top: 4px; }
        .kaki-surat { margin: 28px 0 0; font-size: 12px; color: #5f6f68; font-family: "Segoe UI", sans-serif; }
        @media (max-width: 640px) {
            .kertas { padding: 22px 16px; }
            .kop-surat .lembaga { padding-right: 0; text-align: left; }
        }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .kertas { width: auto; margin: 0; box-shadow: none; padding: 0; }
            .tingkat-surat { background: white !important; }
        }
    </style>
</head>
<body>
    @php
        $daftar = $surat->santri->pelanggaran
            ->filter(fn ($item) => $item->tanggal_pelanggaran->lte($surat->tanggal_surat))
            ->sortBy('tanggal_pelanggaran')
            ->values();
        $paragraf = preg_split("/\n\s*\n/", trim($surat->isi_surat)) ?: [];
    @endphp

    <div class="alat-cetak no-print">
        <button class="btn-hijau" type="button" onclick="window.print()">Cetak</button>
        <a class="btn-putih" href="{{ route('surat.show', $surat) }}">Kembali</a>
    </div>

    <article class="kertas">
        <header class="kop-surat">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo pondok">
            @endif
            <div class="lembaga">
                <div class="merek">SIMONSA</div>
                <h1>{{ $namaPondok }}</h1>
                <p>Sistem Monitoring Pelanggaran Santri</p>
            </div>
        </header>
        <div class="garis-kop"></div>

        <h2 class="judul-surat">SURAT PERINGATAN</h2>
        <p class="nomor-surat">
            Nomor: {{ $surat->nomor_surat }}<br>
            <span class="tingkat-surat {{ strtolower($surat->tingkat_peringatan) }}">{{ $surat->tingkat_peringatan }}</span>
        </p>

        <table class="meta-surat">
            <tr><td>Tanggal</td><td>:</td><td>{{ $kota }}, {{ $surat->tanggal_surat->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Nama santri</td><td>:</td><td>{{ $surat->santri->nama_santri }}</td></tr>
            <tr><td>NIS</td><td>:</td><td>{{ $surat->santri->nis }}</td></tr>
            <tr><td>Kelas / kamar</td><td>:</td><td>{{ $surat->santri->kelas ?: '-' }} / {{ $surat->santri->kamar ?: '-' }}</td></tr>
            <tr><td>Total poin</td><td>:</td><td>{{ $surat->total_poin }}</td></tr>
        </table>

        <div class="isi-surat">
            @foreach($paragraf as $teks)
                <p>{{ $teks }}</p>
            @endforeach
        </div>

        <table class="tabel-surat">
            <thead>
                <tr><th class="no">No</th><th>Tanggal</th><th>Pelanggaran</th><th class="angka">Poin</th></tr>
            </thead>
            <tbody>
            @forelse($daftar as $item)
                <tr>
                    <td class="no">{{ $loop->iteration }}</td>
                    <td>{{ $item->tanggal_pelanggaran->translatedFormat('d M Y') }}</td>
                    <td>{{ $item->kategori->nama_pelanggaran }}</td>
                    <td class="angka">{{ $item->poin_pelanggaran }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Belum ada catatan pelanggaran sampai tanggal surat.</td></tr>
            @endforelse
            </tbody>
            @if($daftar->isNotEmpty())
                <tfoot>
                    <tr>
                        <td colspan="3">Jumlah poin</td>
                        <td class="angka">{{ $daftar->sum('poin_pelanggaran') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>

        <div class="penutup">
            <div class="ttd">
                <div>{{ $kota }}, {{ $surat->tanggal_surat->translatedFormat('d F Y') }}</div>
                <div>Petugas Keamanan</div>
                <div class="ruang"></div>
                <strong>{{ $surat->pengguna->nama_user }}</strong>
            </div>
        </div>
        <p class="kaki-surat">Keputusan sanksi dilakukan di luar sistem oleh pengasuh pondok.</p>
    </article>
</body>
</html>
