<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    // ...

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Sempre JSON 401 para API (evita route('login'))
        return response()->json([
            'message' => 'Não autenticado.',
        ], 401);
    }
}
