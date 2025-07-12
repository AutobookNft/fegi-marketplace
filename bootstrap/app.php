<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Dotenv\Dotenv;

// 🔐 Load .env early to avoid "No application encryption key" errors
Dotenv::createImmutable(dirname(__DIR__))->load();

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Force HTTPS recognition per PeraWallet (VITALE!)
        $middleware->web(prepend: [
            \App\Http\Middleware\ForceHttpsMiddleware::class,
        ]);

        // Founders System: Wallet-based authentication middleware
        $middleware->alias([
            'wallet.auth' => \App\Http\Middleware\WalletAuthMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
