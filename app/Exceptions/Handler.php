<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
{
    if ($request->is('api/*')) {

        if ($exception instanceof AuthenticationException) {
            return response()->json(['error' => 'Não autorizado.'], 401);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => $exception->validator->errors()->first(),
                'errors' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof HttpExceptionInterface) {
            return response()->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        }

        return response()->json([
            'error' => $exception->getMessage(),
            'trace' => config('app.debug') ? $exception->getTrace() : null,
        ], 500);
    }

    return parent::render($request, $exception);
}
}
