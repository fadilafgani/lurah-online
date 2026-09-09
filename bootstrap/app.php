<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // HTTPS diterminasi oleh proxy di depan (Cloudflare/Nginx), lalu
        // diteruskan sebagai HTTP ke Caddy. Tanpa ini Laravel menganggap
        // request sebagai http:// sehingga URL & redirect ikut salah skema.
        // Satu-satunya jalan masuk ke container adalah Caddy, jadi batasi
        // port 80 VPS ke IP proxy lewat firewall (lihat DEPLOYMENT.md).
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
