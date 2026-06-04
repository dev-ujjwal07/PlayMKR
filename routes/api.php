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

Route::post(
    '/approve-sponsor',
    [SponsorController::class, 'approveSponsor']
);