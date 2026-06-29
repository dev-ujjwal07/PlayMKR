<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Constants\AuthConstants;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exceptions\InvoiceNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


use App\Models\Report;
use App\Models\Ticket;
use App\Models\Deal;
use App\Models\Sponsor;
use App\Models\Deliverable;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
 ->withMiddleware(function (Middleware $middleware): void {

    $middleware->redirectGuestsTo(
        fn () => null
    );

    $middleware->alias([

        'admin' =>
            \App\Http\Middleware\AdminMiddleware::class,

    ]);

})
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (HttpException $e) {

            return response()->json([

                'status' => false,

                'message' => $e->getMessage()

            ], $e->getStatusCode());

        });

        $exceptions->render(
            function (
                AuthenticationException $e
            ) {

                return response()->json([

                    'status' => false,

                    'message' => 'Unauthenticated.'

                ], 401);
            }
        );

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e) {

            return response()->json([

                'status' => false,

                'message' => 'Validation Error',

                'errors' => $e->errors()

            ], 422);

        });

        $exceptions->render(
            function (
                \App\Exceptions\SponsorAlreadyApprovedException $e
            ) {

                return response()->json([

                    'status' => false,

                    'message' => $e->getMessage()

                ], 422);
            }
        );




$exceptions->render(
    function (
        ModelNotFoundException $e
    ) {

        $model = class_basename(
            $e->getModel()
        );

        $message = match ($model) {

            'Report' =>
                'Report not found',

            'Ticket' =>
                'Ticket not found',

            'Deliverable' =>
                'Deliverable not found',

            'Sponsor' =>
                'Sponsor not found',

            'Deal' =>
                'Deal not found',

            default =>
                'Record not found',
        };

        return response()->json([

            'status' => false,

            'message' => $message

        ], 404);
    }
);









$exceptions->render(
    function (
        InvoiceNotFoundException $e
    ) {

        return response()->json([

            'status' => false,

            'message' =>
                $e->getMessage()

        ], 404);
    }
);







        $exceptions->render(function (\Exception $e) {

            $statusCode = 500;

              if (
        $e instanceof InvoiceNotFoundException
    ) {
        $statusCode = 404;
    }

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