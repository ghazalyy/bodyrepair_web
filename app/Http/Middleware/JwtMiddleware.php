<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\JwtService;

class JwtMiddleware
{
    public function __construct(private JwtService $jwt) {}

    public function handle(Request $request, Closure $next): Response
    {
        // Coba ambil token dari header Authorization, fallback ke cookie
        $token = $this->extractToken($request);

        if (!$token) {
            return $this->unauthorised($request, 'Token JWT tidak ditemukan. Silakan login kembali.');
        }

        $payload = $this->jwt->validate($token);

        if (!$payload) {
            return $this->unauthorised($request, 'Sesi keamanan tidak valid atau sudah habis. Silakan login kembali.');
        }

        // Verifikasi: jika session Laravel ada tapi tidak cocok dengan JWT,
        // bersihkan session lama dan biarkan JWT jadi acuan (tidak error)
        if (auth()->check() && auth()->id() !== (int) $payload->sub) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Simpan payload di request agar controller bisa akses jika perlu
        // Konversi stdClass ke array karena merge() tidak bisa menerima object
        $request->merge(['jwt_payload' => (array) $payload]);

        // Login user ke Laravel auth agar auth()->user() tersedia di blade/controller
        if (!auth()->check() || auth()->id() !== (int) $payload->sub) {
            $user = \App\Models\User::find((int) $payload->sub);
            if (!$user) {
                return $this->unauthorised($request, 'User tidak ditemukan. Silakan login kembali.');
            }
            auth()->login($user);
        }

        return $next($request);
    }

    private function extractToken(Request $request): ?string
    {
        // 1. Authorization: Bearer <token>
        $bearer = $request->bearerToken();
        if ($bearer) {
            return $bearer;
        }

        // 2. Cookie httpOnly bernama 'jwt_token'
        return $request->cookie('jwt_token');
    }

    private function unauthorised(Request $request, string $message): Response
    {
        // Hapus session & cookie JWT
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // AJAX / JSON request → kembalikan 401
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['error' => $message, 'redirect' => route('login')], 401);
        }

        return redirect()->route('login')
            ->withErrors(['jwt' => $message])
            ->with('error', $message);
    }
}
