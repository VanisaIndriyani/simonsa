<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akses ditolak · SIMONSA</title>
    <link rel="stylesheet" href="{{ asset('css/simonsa.css') }}">
</head>
<body style="display:grid;place-items:center;min-height:100vh;background:#101418;color:white">
    <div style="text-align:center;padding:24px">
        <div class="logo-fallback" style="margin:0 auto 16px">S</div>
        <h1>Akses ditolak</h1>
        <p style="color:#c9d6cf">{{ $exception->getMessage() ?: 'Anda tidak memiliki akses ke halaman ini.' }}</p>
        <a class="btn-hijau" href="{{ url('/dashboard') }}">Kembali</a>
    </div>
</body>
</html>
