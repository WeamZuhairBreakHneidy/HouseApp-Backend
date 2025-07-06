<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    // existing $dontReport and $dontFlash

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'data' => $exception->errors(),
        ], 422);
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof ValidationException) {
                return $this->invalidJson($request, $exception);
            }

            if ($exception instanceof UnauthorizedHttpException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 401);
            }

            if ($exception instanceof QueryException) {
                return response()->json([
                    'status' => false,
                    'message' => 'A database error occurred',
                    'data' => $exception->getMessage(), // you can remove or hide details in production
                ], 500);
            }

            return response()->json([
                'status' => false,
                'message' => $exception->getMessage() ?: 'Something went wrong',
                'data' => null,
            ], method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
        }

        return parent::render($request, $exception);
    }
}
