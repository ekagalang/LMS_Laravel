<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // Pastikan ini di-import

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php', // Jika Anda menggunakan API routes
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware Anda di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class, // BARIS INI YANG PENTING
            // Anda bisa menambahkan alias lain jika dibutuhkan, contoh:
            // 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            // 'auth' => \App\Http\Middleware\Authenticate::class,
            // Catatan: 'auth' dan 'guest' biasanya sudah dikonfigurasi oleh Laravel Breeze/Jetstream jika Anda menggunakannya.
        ]);

        // Anda juga bisa mendaftarkan middleware global di sini, contoh:
        // $middleware->use([
        //     \Illuminate\Http\Middleware\TrustProxies::class,
        // ]);

        // Atau middleware group, contoh:
        // $middleware->group('web', [
        //     \App\Http\Middleware\EncryptCookies::class,
        //     \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        //     // ...
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Konfigurasi penanganan eksepsi
        // ...
    })->create();