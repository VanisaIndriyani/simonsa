<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Dashboard') · SIMONSA</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/simonsa.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">
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
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
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

        @if(session('sukses'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('sukses')), confirmButtonColor: '#1b8f4a' });
        @elseif(session('gagal'))
        Swal.fire({ icon: 'error', title: 'Gagal', text: @json(session('gagal')), confirmButtonColor: '#b42318' });
        @elseif(session('peringatan'))
        Swal.fire({ icon: 'warning', title: 'Perhatian', text: @json(session('peringatan')), confirmButtonColor: '#e0a11a' });
        @endif

        document.querySelectorAll('form').forEach((form) => {
            const metode = form.querySelector('input[name="_method"]');
            if (metode && metode.value === 'DELETE') {
                form.addEventListener('submit', (e) => {
                    if (form.dataset.yakin === '1') return;
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Hapus data?',
                        text: 'Anda yakin ingin menghapus data ini?',
                        showCancelButton: true,
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#b42318',
                        cancelButtonColor: '#6b7280'
                    }).then((hasil) => {
                        if (!hasil.isConfirmed) return;
                        form.dataset.yakin = '1';
                        form.submit();
                    });
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
