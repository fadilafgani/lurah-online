<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Kebijakan Satu Role Aktif: jika sesi warga sedang aktif, tolak dengan 403
        if (Auth::guard('resident')->check()) {
            return response()->json([
                'message' => 'Anda sedang masuk sebagai warga. Silakan logout terlebih dahulu untuk masuk sebagai admin.'
            ], 403);
        }

        // 2. Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Guard ditulis eksplisit: begitu ada permintaan yang lewat
        // "auth:sanctum", guard default ikut berpindah ke sanctum, dan
        // RequestGuard tidak punya attempt() sehingga login ikut rusak.
        $guard = Auth::guard('web');

        // 3. Cek login
        if (!$guard->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        // 4. Ambil user dan pastikan role berwenang (admin atau unit)
        $user = $guard->user();
        if (!$user->isAdmin() && !$user->isUnit()) {
            $guard->logout();
            return response()->json([
                'message' => 'Akun tidak memiliki hak akses admin.'
            ], 403);
        }

        // Regenerate session jika request memiliki session web
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        // 5. Buat token Sanctum
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            // Halaman admin memakai ini untuk langsung tahu menu apa yang
            // boleh tampil, tanpa menunggu permintaan /api/me.
            'identity' => $user->toDisplayArray(),
            'token' => $token,
        ]);
    }

    /**
     * Logout admin: cabut token Sanctum dan akhiri sesi web.
     */
    public function logout(Request $request)
    {
        $user = Auth::guard('web')->user() ?? $request->user('sanctum');

        if ($user) {
            $token = $request->user('sanctum')?->currentAccessToken();
            if ($token && method_exists($token, 'delete')) {
                $token->delete();
            } elseif (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }

            Auth::guard('web')->logout();
        }

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Logout berhasil']);
        }

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Identitas pemilik token yang sedang dipakai.
     */
    public function me(Request $request)
    {
        return response()->json(['data' => $request->user()->toDisplayArray()]);
    }
}