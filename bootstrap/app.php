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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(\App\Http\Middleware\RealIpMiddleware::class);
        $middleware->append(\App\Http\Middleware\CheckIpBlacklist::class);
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: [
            'logout',
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\LogUserActivity::class,
        ]);
        $middleware->alias([
            'hams.report.access' => \App\Http\Middleware\HamsReportAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (\Throwable $e) {
            // Ignore some common HTTP exceptions if needed
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return;
            }
            
            activity('error')
                ->withProperties([
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => substr($e->getTraceAsString(), 0, 1000)
                ])
                ->log($e->getMessage() ?: 'System Exception');
        });
    })->create();
