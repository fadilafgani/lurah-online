<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureResidentAuthenticated
{
    /**
     * Pastikan warga sudah login, berstatus aktif, dan telah mengubah password awal bila diwajibkan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('resident')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('warga.login'))
                ->with('warning', 'Silakan masuk menggunakan NIK Anda untuk membuat pengaduan.');
        }

        $resident = Auth::guard('resident')->user();

        if (!$resident->isActive()) {
            Auth::guard('resident')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun warga nonaktif.'], 403);
            }

            return redirect()->route('warga.login')
                ->withErrors(['nik' => 'Akun warga berstatus nonaktif. Silakan hubungi kantor desa/kelurahan.']);
        }

        // Jika warga masih memakai kata sandi awal (NIK), wajibkan ganti kata sandi
        if ($resident->must_change_password &&
            !$request->routeIs('warga.ganti-password*') &&
            !$request->routeIs('warga.logout')) {
            return redirect()->route('warga.ganti-password')
                ->with('warning', 'Demi keamanan akun Anda, silakan ubah kata sandi awal terlebih dahulu sebelum membuat pengaduan.');
        }

        return $next($request);
    }
}
