<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
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
                'data' => $exception->getMessage(), // Remove or mask in production
            ], 500);
        }

        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : 500;

        return response()->json([
            'status' => false,
            'message' => $exception->getMessage() ?: 'Something went wrong',
            'data' => null,
        ], $statusCode);
    }

    return parent::render($request, $exception);
}

}
