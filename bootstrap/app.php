<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Constants\AuthConstants;

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
   ->withExceptions(function (Exceptions $exceptions) {

    $exceptions->render(function (\Illuminate\Validation\ValidationException $e) {

        return response()->json([

            'status' => false,

            'message' => 'Validation Error',

            'errors' => $e->errors()

        ], 422);

    });

 $exceptions->render(function (\Exception $e) {

    $statusCode = 500;

    if (
        $e->getMessage() === 'User not found' ||
        $e->getMessage() === AuthConstants::EMAIL_NOT_FOUND
    ) {
        $statusCode = 404;
    }

    if (
        $e->getMessage() === 'Invalid credentials' ||
        $e->getMessage() === AuthConstants::INVALID_TOKEN
    ) {
        $statusCode = 401;
    }

    return response()->json([

        'status' => false,

        'message' => $e->getMessage()

    ], $statusCode);

});

})->create();
