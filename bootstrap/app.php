<?php

use Illuminate\Http\Request;
use App\Http\Middleware\Cors;
use App\Http\Middleware\Lang;
use App\Http\Middleware\Role;
use Illuminate\Foundation\Application;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthorActiveMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'lang' => Lang::class,
            'cors' => Cors::class,
            'role' => Role::class,
            'admin' => AdminMiddleware::class,
            'author' => AuthorActiveMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($e instanceof NotFoundHttpException) {
                return response()->json(['error' => 'Resource not found'], 404);
            }
            if ($request->is('api/*')) {
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 422;
                return response()->json([
                    'error' => [
                        'message' => $e->getMessage(),
                        'status_code' => $statusCode
                    ]
                ], $statusCode);
            }
        });
    })->create();
