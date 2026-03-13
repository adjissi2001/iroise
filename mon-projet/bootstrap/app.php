<?php

use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureProfileValidated;
use App\Http\Middleware\NoCacheHeaders;
use App\Http\Middleware\EnsureUserHasRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withSchedule(function (Schedule $schedule): void {
        // Purge automatique des comptes non validés (24h pour le test)
        $schedule->command('users:purge-unvalidated --hours=24 --force')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // Enregistrement du middleware admin
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'profile.validated' => EnsureProfileValidated::class,
            'no.cache' => NoCacheHeaders::class,
            'role' => EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
