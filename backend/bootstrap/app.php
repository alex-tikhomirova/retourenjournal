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


        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        $middleware->alias([
            'org.current' => \App\Http\Middleware\EnsureCurrentOrganization::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 401 — неавторизован
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        });

        // 403 — нет прав
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            return response()->json([
                'message' => 'Forbidden',
            ], 403);
        });

        // 404 — маршрут или модель не найдена
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        });

        // 405 — метод не разрешён
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'message' => 'Method Not Allowed',
            ], 405);
        });

        // 419 — CSRF / expired
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return response()->json([
                'message' => 'Page Expired',
            ], 419);
        });

        // 422 — валидация
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors(),
            ], 422);
        });

        // 429 — слишком много запросов
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, $request) {
            return response()->json([
                'message' => 'Too Many Requests',
            ], 429);
        });

        // 500 — все остальные ошибки
        $exceptions->render(function (\Throwable $e, $request) {

            // Если debug включён — показываем сообщение
            if (config('app.debug')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'exception' => class_basename($e),
                    'trace' => collect($e->getTrace())->take(5), // короткий trace
                ], 500);
            }

            // В проде — только общее сообщение
            return response()->json([
                'message' => 'Server Error',
            ], 500);
        });


    })->create();
