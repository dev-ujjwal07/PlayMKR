<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Constants\AuthConstants;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateAdminProfileRequest;

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

  public function login(LoginRequest $request)
{
    $result = $this->authService
                   ->login($request->validated());

    return response()->json([
        'status'  => true,
        'message' => 'Login Successfully',
        'data'    => $result
    ], 200);
}


public function forgotPassword(
    ForgotPasswordRequest $request
)
{
    $this->authService
         ->forgotPassword(
             $request->validated()
         );

    return response()->json([

        'status' => true,

        'message' =>
            AuthConstants::RESET_LINK_SENT

    ]);
}





public function resetPassword(ResetPasswordRequest $request)
{
    $this->authService->resetPassword($request->validated());

    return response()->json([
        'status' => true,
        'message' => 'Password reset successfully'
    ]);
}

public function updateProfile(
    UpdateAdminProfileRequest $request
)
{
    $response =
        $this->authService
            ->updateProfile(
                $request->validated()
            );

    return response()->json(
        $response
    );
}



public function getProfile()
{
    return response()->json(

        $this->authService
            ->getProfile()

    );
}


}