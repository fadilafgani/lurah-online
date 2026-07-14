<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan halaman lupa password.
     */
    public function create()
    {
        return view('admin.forgot-password');
    }

    /**
     * Mengirim email reset password.
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()
                ->route('admin.check-email')
                ->with('email', $request->email);
        }

        return back()
            ->withInput()
            ->withErrors([
                'email' => __($status),
            ]);
    }
}