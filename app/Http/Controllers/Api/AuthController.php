<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(
        AuthService $authService
    )
    {
        $this->authService = $authService;
    }

public function register(RegisterRequest $request)
{
    $user = $this->authService
                 ->register($request->validated());

    return response()->json([

        'status' => true,

        'message' => 'Account Created Successfully',

        'data' => $user

    ], 201);
}



}