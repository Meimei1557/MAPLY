<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([__DIR__.'/../app/Console/Commands'])
    ->withMiddleware(function (Middleware $middleware): void {
        // Tamu yang membuka halaman terproteksi diarahkan ke login.
        $middleware->redirectGuestsTo(fn () => route('login'));
        // Pengguna yang sudah login dan membuka /login atau /register diarahkan ke Beranda
        // (bawaan Laravel mengarahkan ke "/", yang menyebabkan loop redirect pada versi lama).
        $middleware->redirectUsersTo(fn () => route('dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    })->create();
