<?php

namespace App\Http\Middleware;

use App\Models\AktivitasLogin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PastikanPenggunaAktif
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->status !== 'aktif') {
            AktivitasLogin::catat($user, 'keluar', $user->username, $request->ip());
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('gagal', 'Akun dinonaktifkan. Hubungi admin.');
        }

        return $next($request);
    }
}
