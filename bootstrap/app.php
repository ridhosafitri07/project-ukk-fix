<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Custom route model bindings
            Route::bind('master_lokasi', function ($value) {
                return \App\Models\Lokasi::where('id_lokasi', $value)->firstOrFail();
            });
            
            Route::bind('master_barang', function ($value) {
                return \App\Models\Item::where('id_item', $value)->firstOrFail();
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
