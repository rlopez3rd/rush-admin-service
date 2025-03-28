<?php

use App\Exceptions\CustomExceptionHandler;
use App\Http\Middleware\JWTAuthMiddleware;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    // ->withRouting(
    //     web: __DIR__.'/../routes/web.php',
    //     commands: __DIR__.'/../routes/console.php',
    //     health: '/up',
    // )
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        // commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.jwt' => JWTAuthMiddleware::class,
            'permission' => PermissionMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $customHandler = new CustomExceptionHandler();

        $exceptions->render(function (Throwable $exception) use ($customHandler) {
            return $customHandler->handle($exception);
        });
        
    })
    ->create();
