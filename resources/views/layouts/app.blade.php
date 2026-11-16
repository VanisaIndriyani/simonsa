<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Dashboard') · SIMONSA</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/simonsa.css') }}">
</head>
<body>
    @include('partials.sidebar')
    <div class="backdrop" id="backdrop"></div>
    <div class="main">
        <header class="topbar">
            <button class="menu-btn" id="toggleSidebar" type="button" aria-label="Menu"><i class="bi bi-list"></i></button>
            @if($logoUrl)
                <img class="logo-top" src="{{ $logoUrl }}" alt="Logo pondok">
            @else
                <div class="logo-top logo-fallback">S</div>
            @endif
            <div>
                <h1>@yield('judul', 'Dashboard')</h1>
                @hasSection('subjudul')<p>@yield('subjudul')</p>@endif
            </div>
            @if(auth()->user()->petugas())
                <a class="lonceng" href="{{ route('notifikasi.index') }}" aria-label="Notifikasi">
                    <i class="bi bi-bell"></i>
                    @if(($jumlahNotif ?? 0) > 0)<em>{{ $jumlahNotif }}</em>@endif
                </a>
            @endif
        </header>
        <div class="isi">
            @include('partials.alert')
            @hasSection('aksi')
                <div class="page-head">
                    <div></div>
                    <div class="page-actions no-print">@yield('aksi')</div>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        const toggle = document.getElementById('toggleSidebar');
        const backdrop = document.getElementById('backdrop');
        const tutup = () => document.body.classList.remove('sidebar-open');
        toggle?.addEventListener('click', () => document.body.classList.toggle('sidebar-open'));
        backdrop?.addEventListener('click', tutup);

        document.querySelectorAll('.sandi .mata').forEach((tombol) => {
            tombol.addEventListener('click', () => {
                const input = tombol.parentElement.querySelector('input');
                const buka = input.type === 'password';
                input.type = buka ? 'text' : 'password';
                tombol.querySelector('i').className = buka ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });

        document.querySelectorAll('form').forEach((form) => {
            const metode = form.querySelector('input[name="_method"]');
            if (metode && metode.value === 'DELETE') {
                form.addEventListener('submit', (e) => {
                    if (!confirm('Anda yakin ingin menghapus data ini?')) e.preventDefault();
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
