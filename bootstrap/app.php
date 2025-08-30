<?php

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
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
         $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'type' => 'unauthenticated',
                        'message' => 'Authentication required',
                        'code' => 401,
                    ],
                ], 401);
            }
        });

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->expectsJson()) {
                $messages = collect($e->errors())->flatten()->implode(', ');
                return response()->json([
                    'success' => false,
                    'error' => [
                        'type' => 'validation_error',
                        'message' => 'Validation failed: ' . $messages, // Set concatenated message
                        'code' => 422,
                    ],
                ], 422);
            }
        });
        //
    })->create();
