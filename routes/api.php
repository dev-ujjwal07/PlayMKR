<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SponsorApplicationController;
use App\Http\Controllers\Api\SponsorController;
use App\Http\Controllers\Api\DealTypeController;
use App\Http\Controllers\Api\DealController;
use App\Http\Controllers\Api\DeliverTypeController;
use App\Http\Controllers\Api\DeliverableController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\InvoiceController;


Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);
Route::post(
    '/forgot-password', 
    [AuthController::class, 'forgotPassword']
);

Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::post(
    '/sponsor-request',
    [SponsorApplicationController::class, 'store']
);

Route::middleware('auth:api')->group(function () {

    Route::post(
        '/approve-sponsor',
        [SponsorController::class, 'approveSponsor']
    );

        Route::post(
        '/sponsor-status',
        [SponsorController::class, 'sponsorStatus']
    );


    Route::post(
        '/add-sponsor',
        [SponsorController::class, 'addSponsor']
    );


        Route::post(
        '/delete-sponsor',
        [SponsorController::class, 'deleteSponsor']
    );




        Route::post(
        '/update-sponsor',
        [SponsorController::class, 'updateSponsor']
    );


        Route::post(
        '/deal-type',
        [DealTypeController::class, 'store']
    );
     Route::post(
        '/deal',
        [DealController::class, 'store']
    );


Route::put(
    '/update-deal/{id}',
    [DealController::class, 'updateDeal']
);


    Route::post(
        '/deliver-type',
        [DeliverTypeController::class, 'store']
    );


        Route::post(
        '/deliverable',
        [DeliverableController::class, 'store']
    );

    Route::post(
    '/attachments',
    [AttachmentController::class, 'store']
);

Route::delete(
    '/deliverables/{id}',
    [DeliverableController::class, 'delete']
);


Route::put(
    '/deliverables/{id}',
    [DeliverableController::class, 'update']
);

Route::post(
    '/invoices',
    [InvoiceController::class, 'store']
);


Route::put(
    '/invoices/{id}',
    [InvoiceController::class, 'update']
);

Route::delete(
    '/invoices/{id}',
    [InvoiceController::class, 'destroy']
);
    

    
});