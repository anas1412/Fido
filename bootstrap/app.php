<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 1. We capture the final application instance in a variable called $app
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// 2. Now that we have the $app object, we can modify it for Electron
/*
|--------------------------------------------------------------------------
| Add this block for Electron Integration
|--------------------------------------------------------------------------
|
| This checks for an environment variable passed by Electron's main process.
| If it exists, it overrides Laravel's default storage path to a writable
| location in the user's data directory. This is crucial for a packaged app.
|
*/
if (env('APP_STORAGE_PATH')) {
    $app->useStoragePath(env('APP_STORAGE_PATH'));
}

// 3. Finally, we return the modified $app object
return $app;