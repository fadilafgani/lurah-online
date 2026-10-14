<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResidentAuthController extends Controller
{
    /**
     * Tampilkan halaman login warga berbasis NIK.
     */
    public function showLogin()
    {
        // 1. Kebijakan Satu Role Aktif: jika sesi admin aktif, tolak dengan 403
        if (Auth::guard('web')->check()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Anda sedang masuk sebagai admin. Silakan logout terlebih dahulu untuk masuk sebagai warga.'
                ], 403);
            }
            abort(403, 'Akses ditolak. Anda sedang masuk sebagai admin. Silakan logout terlebih dahulu untuk masuk sebagai warga.');
        }

        if (Auth::guard('resident')->check()) {
            $resident = Auth::guard('resident')->user();
            if ($resident->must_change_password) {
                return redirect()->route('warga.ganti-password');
            }
            return redirect()->route('pengaduan.buat');
        }

        return view('warga.login');
    }

    /**
     * Proses autentikasi warga:
     * - Validasi NIK dan Kata Sandi
     * - Cek keberadaan akun di tabel residents
     * - Cek status aktif
     * - Arahkan ke ganti sandi jika masih menggunakan sandi awal
     */
    public function login(Request $request)
    {
        // 1. Kebijakan Satu Role Aktif: tolak jika admin sedang login
        if (Auth::guard('web')->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda sedang masuk sebagai admin. Silakan logout terlebih dahulu.'
                ], 403);
            }
            abort(403, 'Akses ditolak. Anda sedang masuk sebagai admin. Silakan logout terlebih dahulu.');
        }

        $credentials = $request->validate([
            'nik' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $resident = Resident::where('nik', $credentials['nik'])->first();

        // ponytail: pesan kesalahan yang spesifik memudahkan warga mengetahui akar masalah
        if (!$resident) {
            return back()
                ->withInput($request->only('nik'))
                ->withErrors(['nik' => 'NIK tidak terdaftar dalam database warga Desa Banjarsari.']);
        }

        if (!$resident->isActive()) {
            return back()
                ->withInput($request->only('nik'))
                ->withErrors(['nik' => 'Akun warga berstatus nonaktif. Silakan hubungi kantor kelurahan/desa.']);
        }

        if (!Hash::check($credentials['password'], $resident->password)) {
            return back()
                ->withInput($request->only('nik'))
                ->withErrors(['password' => 'Kata sandi yang Anda masukkan salah.']);
        }

        Auth::guard('resident')->login($resident, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($resident->must_change_password) {
            return redirect()->route('warga.ganti-password')
                ->with('info', 'Selamat datang! Karena Anda menggunakan kata sandi awal (NIK), silakan buat kata sandi baru untuk keamanan akun Anda.');
        }

        return redirect()->intended(route('pengaduan.buat'));
    }

    /**
     * Logout warga.
     */
    public function logout(Request $request)
    {
        Auth::guard('resident')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Logout berhasil']);
        }

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Tampilkan halaman ubah kata sandi.
     */
    public function showChangePassword()
    {
        $resident = Auth::guard('resident')->user();
        return view('warga.ganti-password', compact('resident'));
    }

    /**
     * Simpan kata sandi baru warga.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $resident = Auth::guard('resident')->user();

        if (!Hash::check($request->current_password, $resident->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.']);
        }

        $resident->update([
            'password' => $request->password,
            'must_change_password' => false,
        ]);

        return redirect()->route('pengaduan.buat')
            ->with('success', 'Kata sandi berhasil diperbarui. Silakan lanjutkan pembuatan pengaduan.');
    }
}
