<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\JwtService;

class AuthController extends Controller
{
    public function __construct(private JwtService $jwt) {}

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email', 'max:150'],
            'password' => ['required', 'min:6'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        // Paksa remember=false agar session cookie tidak persistent
        if (Auth::attempt($credentials, false)) {
            $request->session()->regenerate();

            // Generate JWT untuk user yang baru login
            $token = $this->jwt->generate(Auth::user());

            // Kirim JWT sebagai httpOnly cookie (expire=0 = session cookie = hilang saat browser tutup)
            // dan juga kirim ke view agar bisa disimpan di sessionStorage
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login berhasil! Selamat datang, ' . Auth::user()->nama . '.')
                ->with('jwt_token', $token)
                ->cookie(
                    'jwt_token',   // nama cookie
                    $token,        // value
                    0,             // minutes=0 → session cookie (hilang saat browser tutup)
                    '/',           // path
                    null,          // domain
                    false,         // secure (set true di production HTTPS)
                    true,          // httpOnly
                    false,         // raw
                    'lax'          // sameSite
                );
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.')
            ->withoutCookie('jwt_token');
    }
}
