<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/reset-password', function () {
    return response()->json([
        'status' => true,
        'message' => 'Reset Password Page Opened'
    ]);
});