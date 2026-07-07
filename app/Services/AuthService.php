<?php

namespace App\Services;

use Exception;
use App\Exceptions\RegistrationException;
use App\Interfaces\AuthRepositoryInterface;
use App\Constants\AuthConstants;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    protected $authRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
         
    )
    {

        $this->authRepository = $authRepository;
    }

    public function register(array $data)
    {
        try {

            return $this->authRepository
                        ->register($data);

        } catch (Exception $e) {

            throw new RegistrationException(
                $e->getMessage()
            );
        }
    }


      public function login(array $data)
{
   $user = $this->authRepository->findUserByEmail($data['email']);

    if (!$user) {
        throw new Exception('User not found');
    }

    if (!Hash::check($data['password'], $user->password)) {
        throw new Exception('Invalid credentials');
    }

$token = $user->createToken('Admin Token')->accessToken;

$user->token = $token;

return $user;
}


public function forgotPassword(array $data)
{
    $user = $this->authRepository
                 ->findUserByEmail(
                     $data['email']
                 );

    if (!$user) {

        throw new Exception(
            AuthConstants::EMAIL_NOT_FOUND
        );
    }

    $token = Str::random(64);

    $this->authRepository
         ->storeResetToken(
             $user->email,
             $token
         );

    $link = url(
        '/reset-password?token=' .
        $token .
        '&email=' .
        $user->email
    );

    Mail::to($user->email)
        ->send(
            new ForgotPasswordMail(
                $link
            )
        );

    return true;
}



public function resetPassword(array $data)
{
    try {

        // 1. Check token
$tokenData = $this->authRepository
    ->findResetToken($data['email']);

if (!$tokenData) {
    throw new Exception(
        AuthConstants::INVALID_TOKEN
    );
}

if (
    !Hash::check(
        $data['token'],
        $tokenData->token
    )
) {
    throw new Exception(
        AuthConstants::INVALID_TOKEN
    );
}

        // 2. Update password
        $this->authRepository
            ->updatePassword($data['email'], $data['password']);

        // 3. Delete token after use
        DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->delete();

        return true;

    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}




public function updateProfile(
    array $data
)
{
    return DB::transaction(

        function () use ($data) {

            $authUser =
                request()->user();

            $user =
                $this->authRepository
                    ->getUserById(
                        $authUser->id
                    );

            if (!$user) {

                throw new Exception(
                    'User not found'
                );
            }

            $profilePath =
                $user->profile;

            /*
            |--------------------------------------------------------------------------
            | Profile Image
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['profile'])
            ) {

                if (
                    $profilePath &&
                    Storage::disk('public')->exists(
                        $profilePath
                    )
                ) {

                    Storage::disk('public')
                        ->delete(
                            $profilePath
                        );
                }

                $file =
                    $data['profile'];

                $fileName =
                    time() .
                    '_' .
                    $file->getClientOriginalName();

                $profilePath =
                    $file->storeAs(

                        'profiles',

                        $fileName,

                        'public'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Update Data
            |--------------------------------------------------------------------------
            */

            $updateData = [];

            if (
                isset($data['name'])
            ) {

                $updateData['name'] =
                    $data['name'];

                $updateData['full_name'] =
                    $data['name'];
            }

            if (
                isset($data['email'])
            ) {

                $updateData['email'] =
                    $data['email'];
            }

            if (
                isset($data['number'])
            ) {

                $updateData['number'] =
                    $data['number'];
            }

            if (
                $profilePath
            ) {

                $updateData['profile'] =
                    $profilePath;
            }

            if (
                !empty($updateData)
            ) {

                $this->authRepository
                    ->updateUser(

                        $user->id,

                        $updateData
                    );
            }
                        /*
            |--------------------------------------------------------------------------
            | Password Update
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['current_password']) ||
                isset($data['new_password']) ||
                isset($data['confirm_password'])
            ) {

                if (
                    !Hash::check(
                        $data['current_password'],
                        $user->password
                    )
                ) {

                    throw new Exception(
                        'Current password is incorrect.'
                    );
                }

                $this->authRepository
                    ->updateUser(

                        $user->id,

                        [

                            'password' =>
                                Hash::make(
                                    $data['new_password']
                                )
                        ]
                    );
            }

            return [

                'status' => true,

                'message' =>
                    'Profile updated successfully.'
            ];
        }
    );
}


public function getProfile()
{
    $authUser =
        request()->user();

    $user =
        $this->authRepository
            ->getUserById(
                $authUser->id
            );

    if (!$user) {

        throw new Exception(
            'User not found'
        );
    }

    return [

        'status' => true,

        'message' =>
            'Profile fetched successfully.',

        'data' => [

            'id' =>
                $user->id,

            'name' =>
                $user->name,

            'email' =>
                $user->email,

            'number' =>
                $user->number,

            'profile' =>
                $user->profile
                    ? asset(
                        'storage/' .
                        $user->profile
                    )
                    : null,

            'created_at' =>
                $user->created_at
        ]
    ];
}


}