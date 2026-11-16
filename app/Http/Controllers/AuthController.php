<?php

namespace App\Http\Controllers;

use App\Models\AktivitasLogin;
use App\Models\Pengguna;
use App\Support\Validasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], Validasi::pesan(), [
            'username' => 'username',
            'password' => 'password',
        ]);

        if (! Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {
            AktivitasLogin::catat(
                Pengguna::query()->where('username', $data['username'])->first(),
                'gagal',
                $data['username'],
                $request->ip(),
            );

            return back()
                ->withInput($request->only('username'))
                ->with('gagal', 'Username atau password salah.');
        }

        if (Auth::user()->status !== 'aktif') {
            AktivitasLogin::catat(Auth::user(), 'gagal', Auth::user()->username, $request->ip());
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('username'))
                ->with('gagal', 'Akun nonaktif. Hubungi admin.');
        }

        $request->session()->regenerate();
        AktivitasLogin::catat(Auth::user(), 'masuk', Auth::user()->username, $request->ip());

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        if ($user = Auth::user()) {
            AktivitasLogin::catat($user, 'keluar', $user->username, $request->ip());
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('sukses', 'Anda sudah keluar.');
    }
}
