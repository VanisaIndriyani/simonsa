@extends('layouts.app')

@section('judul', 'Laporan dan Grafik')
@section('subjudul', 'Rekap pelanggaran sesuai saringan')

@section('aksi')
    @if(auth()->user()->admin())
        <button class="btn-putih" id="cetakLaporan" type="button" data-url="{{ route('laporan.cetak') }}" data-token="{{ csrf_token() }}">Cetak laporan</button>
    @endif
@endsection

@section('content')
    <header class="kop-cetak">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="Logo pondok">
        @endif
        <div>
            <div class="merek">SIMONSA</div>
            <strong>{{ $namaPondok }}</strong>
            <p>Sistem Monitoring Pelanggaran Santri</p>
        </div>
        <div class="judul-cetak">
            <b>LAPORAN PELANGGARAN SANTRI</b>
            <span>Dicetak {{ now()->translatedFormat('d F Y H:i') }} WIB</span>
        </div>
    </header>

    <form class="kartu kartu-isi filter mb-3 no-print" method="GET">
        <input class="form-control" name="q" value="{{ $filter['q'] ?? '' }}" placeholder="Nama atau NIS">
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
        <button class="btn-hijau" type="submit">Terapkan</button>
    </form>

    <div class="stat-grid mb-3">
        <article class="stat hijau">
            <div><span>Kasus</span><b>{{ $ringkas['jumlah'] }}</b></div>
            <div class="stat-ikon"><i class="bi bi-journal-x"></i></div>
        </article>
        <article class="stat kuning">
            <div><span>Total poin</span><b>{{ $ringkas['poin'] }}</b></div>
            <div class="stat-ikon"><i class="bi bi-123"></i></div>
        </article>
        <article class="stat hitam">
            <div><span>Santri terlibat</span><b>{{ $ringkas['santri'] }}</b></div>
            <div class="stat-ikon"><i class="bi bi-people"></i></div>
        </article>
        <article class="stat merah">
            <div><span>Batas SP</span><b style="font-size:18px">{{ $batas['sp1'] }} / {{ $batas['sp2'] }} / {{ $batas['sp3'] }}</b></div>
            <div class="stat-ikon"><i class="bi bi-flag"></i></div>
        </article>
    </div>

    <div class="row g-3 mb-3" id="grafik">
        <div class="col-lg-7 grafik-kolom">
            <div class="kartu kartu-isi kartu-aksen hijau">
                <strong>Jumlah pelanggaran</strong>
                <div class="chart-box mt-3"><canvas id="grafikBulan"></canvas></div>
            </div>
        </div>
        <div class="col-lg-5 grafik-kolom">
            <div class="kartu kartu-isi kartu-aksen kuning">
                <strong>Per kategori</strong>
                <div class="chart-box donat mt-3"><canvas id="grafikKategori"></canvas></div>
            </div>
        </div>
    </div>

    <div class="kartu kartu-aksen" id="tabel">
        <div class="table-responsive">
            <table class="table tabel-santri">
                <thead><tr><th class="kolom-no">No</th><th>Tanggal</th><th>Santri</th><th>Pelanggaran</th><th>Poin</th><th>Petugas</th></tr></thead>
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
                    </tr>
                @empty
                    <tr><td colspan="6" class="kosong">Tidak ada data pada saringan ini.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pager no-print">{{ $data->links() }}</div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>
<script>
    const warna = ['#1b8f4a', '#e0a11a', '#c2410c', '#b42318', '#14657a', '#4c3bb5', '#1aa58a', '#d4537e', '#3d5a80', '#8a6a2f'];
    const bulan = @json($chart['nilaiBulan']);
    new Chart(document.getElementById('grafikBulan'), {
        type: 'bar',
        data: { labels: @json($chart['labelBulan']), datasets: [{ data: bulan, backgroundColor: bulan.map((_, i) => warna[i % warna.length]), borderRadius: 8 }] },
        options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
    document.getElementById('cetakLaporan')?.addEventListener('click', async (event) => {
        const tombol = event.currentTarget;
        await fetch(tombol.dataset.url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': tombol.dataset.token, 'Accept': 'application/json' }
        });
        window.print();
    });
    new Chart(document.getElementById('grafikKategori'), {
        type: 'doughnut',
        data: { labels: @json($chart['labelKategori']), datasets: [{ data: @json($chart['nilaiKategori']), backgroundColor: warna }] },
        options: {
            maintainAspectRatio: false,
            cutout: '58%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 8, font: { size: 11 } } }
            }
        }
    });
</script>
@endpush
