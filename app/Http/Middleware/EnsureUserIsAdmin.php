<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Hanya admin kelurahan yang boleh mengelola akun unit; akun unit sendiri
     * tidak boleh membuat atau menghapus akun lain.
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isAdmin(), 403, 'Hanya admin yang boleh mengelola akun unit.');

        return $next($request);
    }
}
