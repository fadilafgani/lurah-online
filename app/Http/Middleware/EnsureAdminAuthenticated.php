<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    /**
     * Pastikan hanya sesi admin/unit yang valid yang dapat mengakses area admin.
     * Sesi warga dilarang keras (HTTP 403). Pengguna belum login diarahkan ke login admin (HTTP 401/302).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kebijakan Satu Role Aktif: Sesi warga dilarang mengakses area admin
        if (Auth::guard('resident')->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akses ditolak. Sesi warga tidak memiliki hak akses ke area admin.'
                ], 403);
            }

            abort(403, 'Akses ditolak. Anda sedang masuk sebagai warga. Silakan logout terlebih dahulu untuk mengakses area admin.');
        }

        // 2. Cek autentikasi admin (web guard untuk web route, sanctum untuk API)
        $user = Auth::guard('web')->user();
        if (!$user && ($request->is('api/*') || $request->expectsJson())) {
            $user = $request->user('sanctum');
        }

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('admin.login'))
                ->with('warning', 'Silakan masuk sebagai admin terlebih dahulu.');
        }

        // 3. Pastikan user memiliki peran admin atau unit
        if (!$user->isAdmin() && !$user->isUnit()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            abort(403, 'Akses ditolak. Akun tidak memiliki hak akses admin.');
        }

        $response = $next($request);

        // 4. Cegah cache browser agar tombol Back tidak menampilkan data terlindungi setelah logout
        return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
