<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // validasi
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Guard ditulis eksplisit: begitu ada permintaan yang lewat
        // "auth:sanctum", guard default ikut berpindah ke sanctum, dan
        // RequestGuard tidak punya attempt() sehingga login ikut rusak.
        $guard = Auth::guard('web');

        // ngecek login
        if (!$guard->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        // ambil user
        $user = $guard->user();

        // buat token
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
     * Identitas pemilik token yang sedang dipakai.
     */
    public function me(Request $request)
    {
        return response()->json(['data' => $request->user()->toDisplayArray()]);
    }
}