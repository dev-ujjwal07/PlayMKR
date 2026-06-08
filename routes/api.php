<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SponsorApplicationController;
use App\Http\Controllers\Api\SponsorController;

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
});