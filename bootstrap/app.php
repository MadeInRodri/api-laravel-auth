<?php

use App\Http\Middleware\IsUserAuth;
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
    //Los middleware que creamos van a air acá. Se carga cuando se carga la API
    ->withMiddleware(function (Middleware $middleware): void {
        IsUserAuth::class;
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
