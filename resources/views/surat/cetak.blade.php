<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $surat->nomor_surat }}</title>
    <link rel="stylesheet" href="{{ asset('css/simonsa.css') }}">
    <style>
        body { background: #e7eee9; margin: 0; }
        .alat-cetak { width: min(820px, 100%); margin: 16px auto 0; display: flex; gap: 8px; }
        .kertas {
            width: min(820px, 100%);
            margin: 16px auto 32px;
            background: white;
            color: #111;
            font-family: "Times New Roman", Times, serif;
            font-size: 16px;
            line-height: 1.45;
            padding: 28px 46px 36px;
            box-shadow: 0 16px 40px rgba(16, 20, 24, .08);
        }
        .kop-gambar { width: 78%; max-width: 520px; display: block; margin: 0 auto 6px; }
        .judul-surat { text-align: center; margin: 8px 0 0; font-size: 18px; font-weight: 700; text-decoration: underline; letter-spacing: .02em; }
        .nomor-surat { text-align: center; margin: 2px 0 16px; text-decoration: underline; }
        .isi-surat { text-align: left; margin: 0 0 8px; }
        .isi-surat p { margin: 0 0 8px; }
        .isian {
            display: grid;
            grid-template-columns: 176px 14px minmax(0, 1fr);
            column-gap: 6px;
            row-gap: 3px;
            align-items: baseline;
            margin: 0 0 12px;
        }
        .isian .penuh { grid-column: 1 / -1; margin: 12px 0 4px; }
        .isian .label { white-space: nowrap; }
        .isian .nilai { line-height: 1.45; }
        .syarat { margin: 4px 0 12px; padding-left: 22px; }
        .syarat li { margin-bottom: 3px; padding-left: 4px; text-align: left; }
        .tanggal-surat { text-align: right; margin: 18px 0 0; padding-right: 36px; }
        .tanda-tangan { display: flex; justify-content: space-between; margin-top: 8px; text-align: center; }
        .ttd { width: 240px; }
        .ttd .ruang { height: 72px; }
        .ttd .nama { margin-top: 4px; }
        .tembusan { margin: 22px 0 0; font-size: 15px; }
        @media (max-width: 640px) { .kertas { padding: 16px; } .tanggal-surat { padding-right: 0; } }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .kertas { width: auto; margin: 0; box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>
    @php
        $daftar = $surat->santri->pelanggaran
            ->filter(fn ($item) => $item->tanggal_pelanggaran->lte($surat->tanggal_surat))
            ->sortBy('tanggal_pelanggaran')
            ->values();
        $jenis = $daftar->map(fn ($item) => $item->kategori->nama_pelanggaran)->unique()->implode(', ');
        $tanggalAwal = $daftar->first()?->tanggal_pelanggaran;
        $tanggalAkhir = $daftar->last()?->tanggal_pelanggaran;
        $rentang = $tanggalAwal
            ? ($tanggalAwal->equalTo($tanggalAkhir)
                ? $tanggalAwal->translatedFormat('d F Y')
                : $tanggalAwal->translatedFormat('d F Y').' – '.$tanggalAkhir->translatedFormat('d F Y'))
            : '-';
        $angkaSp = preg_replace('/\D/', '', $surat->tingkat_peringatan);
    @endphp

    <div class="alat-cetak no-print">
        <button class="btn-hijau" type="button" onclick="window.print()">Cetak</button>
        <a class="btn-putih" href="{{ route('surat.show', $surat) }}">Kembali</a>
    </div>

    <article class="kertas">
        <img class="kop-gambar" src="{{ asset('img/kop.jpeg') }}" alt="Kop Pondok Pesantren Putri Al-Huda">

        <h1 class="judul-surat">SURAT PERINGATAN</h1>
        <p class="nomor-surat">Nomor: {{ $surat->nomor_surat }}</p>

        <div class="isian">
            <p class="penuh">Diberikan kepada santri berikut:</p>
            <div class="label">Nama Santri</div><div>:</div><div class="nilai">{{ $surat->santri->nama_santri }}</div>
            <div class="label">NIS</div><div>:</div><div class="nilai">{{ $surat->santri->nis }}</div>
            <div class="label">Kamar</div><div>:</div><div class="nilai">{{ $surat->santri->kamar ?: '-' }}</div>
            <div class="label">Sekolah/Kelas</div><div>:</div><div class="nilai">{{ $surat->santri->kelas ?: '-' }}</div>

            <p class="penuh">Berdasarkan hasil pemeriksaan/pencatatan oleh bagian Keamanan Pondok, santri tersebut tercatat melakukan pelanggaran tata tertib sebagai berikut:</p>
            <div class="label">Jenis pelanggaran</div><div>:</div>
            <div class="nilai">
                @forelse($daftar->unique('id_kategori') as $item)
                    {{ $loop->iteration }}. {{ $item->kategori->nama_pelanggaran }}@if(! $loop->last)<br>@endif
                @empty
                    -
                @endforelse
            </div>
            <div class="label">Tanggal pelanggaran</div><div>:</div><div class="nilai">{{ $rentang }}</div>
            <div class="label">Akumulasi poin</div><div>:</div><div class="nilai">{{ $surat->total_poin }} poin</div>
        </div>

        <p class="isi-surat">Dengan ini, pihak pondok pesantren memberikan <strong>SURAT PERINGATAN (SP{{ $angkaSp }})</strong> kepada santri yang bersangkutan agar:</p>
        <ol class="syarat">
            <li>Tidak mengulangi pelanggaran tata tertib pondok pesantren.</li>
            <li>Mematuhi seluruh peraturan dan ketentuan yang berlaku di lingkungan pondok pesantren.</li>
            <li>Bersedia menerima pembinaan dan tindak lanjut sesuai ketentuan pondok pesantren apabila kembali melakukan pelanggaran.</li>
        </ol>
        <p class="isi-surat">Demikian surat peringatan ini disampaikan untuk diperhatikan dan dilaksanakan dengan penuh tanggung jawab.</p>

        <div class="tanggal-surat">{{ $kota }}, {{ $surat->tanggal_surat->translatedFormat('d F Y') }}</div>
        <div class="tanda-tangan">
            <div class="ttd">
                <div>Mengetahui,</div>
                <div>Lurah Pondok</div>
                <div class="ruang"></div>
                <div class="nama">(.................................)</div>
            </div>
            <div class="ttd">
                <div>&nbsp;</div>
                <div>Bagian Keamanan Pondok</div>
                <div class="ruang"></div>
                <div class="nama">({{ $surat->pengguna->nama_user }})</div>
            </div>
        </div>
        <p class="tembusan">Tembusan: Orang tua/wali santri (apabila diperlukan).</p>
    </article>
</body>
</html>
