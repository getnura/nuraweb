<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ThemeMiddleware;
use App\Models\Plugin;
use App\Models\Config;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware(['web', 'auth', 'verified', 'AdminMiddleware', 'ThemeMiddleware'])->group(base_path('routes/admin.php'));
            Route::middleware(['web', 'ThemeMiddleware'])->group(base_path('routes/web.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'AdminMiddleware' => AdminMiddleware::class,
            'ThemeMiddleware' => ThemeMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Exception $e) {
            if ($e->getPrevious() instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect()->back()->withInput(request()->except('_token'))->withError('Invalid token. Please submit the form again');
            };
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->view('errors.404', [], 404);
        });
    })->create();
