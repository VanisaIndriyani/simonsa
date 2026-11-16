@php
    $user = auth()->user();
    $awal = strtoupper(mb_substr($user->nama_user, 0, 1));
    $item = function ($label, $route, $ikon, $pola) {
        $aktif = request()->routeIs($pola) ? 'aktif' : '';
        return '<a class="'.$aktif.'" href="'.route($route).'"><i class="bi '.$ikon.'"></i><span>'.$label.'</span></a>';
    };
@endphp
<aside class="sidebar">
    <div class="brand">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="Logo pondok">
        @else
            <div class="logo-fallback">S</div>
        @endif
        <div>
            <strong>SIMONSA</strong>
            <span>{{ $namaPondok }}</span>
        </div>
    </div>
    <nav class="menu">
        <div class="menu-grup">Utama</div>
        {!! $item('Dashboard', 'dashboard', 'bi-grid-1x2', 'dashboard') !!}

        <div class="menu-grup">Data</div>
        {!! $item('Data Santri', 'santri.index', 'bi-people', 'santri.*') !!}
        @if($user->petugas())
            {!! $item('Kategori Pelanggaran', 'kategori.index', 'bi-tags', 'kategori.*') !!}
        @endif
        {!! $item('Data Pelanggaran', 'pelanggaran.index', 'bi-journal-x', 'pelanggaran.*') !!}
        @if($user->petugas())
            {!! $item('Surat Peringatan', 'surat.index', 'bi-envelope-paper', 'surat.*') !!}
            {!! $item("Ta'zir", 'tazir.index', 'bi-exclamation-diamond', 'tazir.*') !!}
        @endif

        <div class="menu-grup">Pemantauan</div>
        {!! $item('Riwayat Pelanggaran', 'riwayat.index', 'bi-clock-history', 'riwayat.*') !!}
        {!! $item('Akumulasi Poin', 'poin.index', 'bi-123', 'poin.*') !!}
        {!! $item('Laporan & Grafik', 'laporan.index', 'bi-bar-chart', 'laporan.*') !!}
        @if($user->petugas())
            {!! $item('Notifikasi', 'notifikasi.index', 'bi-bell', 'notifikasi.*') !!}
        @endif

        @if($user->admin())
            <div class="menu-grup">Sistem</div>
            {!! $item('Pengguna', 'pengguna.index', 'bi-person-badge', 'pengguna.*') !!}
            {!! $item('Aktivitas Login', 'aktivitas.index', 'bi-person-check', 'aktivitas.*') !!}
            {!! $item('Pengaturan Sistem', 'pengaturan.index', 'bi-gear', 'pengaturan.*') !!}
            {!! $item('Backup Database', 'backup.index', 'bi-database', 'backup.*') !!}
        @endif
    </nav>
    <div class="sidebar-user">
        <div class="avatar">{{ $awal }}</div>
        <div>
            <strong>{{ $user->nama_user }}</strong>
            <span>{{ $user->namaRole() }}</span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="keluar" type="submit" title="Logout" aria-label="Logout"><i class="bi bi-box-arrow-right"></i></button>
        </form>
    </div>
</aside>
