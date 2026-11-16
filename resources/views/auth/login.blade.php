<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · SIMONSA</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/simonsa.css') }}">
</head>
<body class="halaman-login" style="--foto-login: url('{{ asset('img/4260901105.webp') }}')">
    <main class="login-box">
        <div class="login-logo">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo pondok">
            @else
                <div class="logo-fallback">S</div>
            @endif
        </div>
        <h1>SIMONSA</h1>
        <p class="lembaga">{{ $namaPondok }}</p>
        @include('partials.alert')
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <div class="bidang">
                    <i class="bi bi-person"></i>
                    <input class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <div class="bidang sandi">
                    <i class="bi bi-lock"></i>
                    <input class="form-control" id="password" name="password" type="password" placeholder="Password" required>
                    <button class="mata" type="button" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                </div>
            </div>
            <button class="btn-hijau w-100 justify-content-center" type="submit" style="height:48px">Masuk</button>
        </form>
    </main>
    <script>
        document.querySelectorAll('.sandi .mata').forEach((tombol) => {
            tombol.addEventListener('click', () => {
                const input = tombol.parentElement.querySelector('input');
                const buka = input.type === 'password';
                input.type = buka ? 'text' : 'password';
                tombol.querySelector('i').className = buka ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });
    </script>
</body>
</html>
