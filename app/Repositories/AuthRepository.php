<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
   return User::create([

    'name' => $data['full_name'],

    'full_name' => $data['full_name'],

    'email' => $data['email'],

    'password' => Hash::make($data['password']),

    'role_id' =>
            1

]);
    }

   public function findUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    

    public function storeResetToken(
    string $email,
    string $token
)
{
    DB::table('password_reset_tokens')
        ->updateOrInsert(

            ['email' => $email],

            [
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );
}



public function findResetToken(
    string $email
)
{
    return DB::table('password_reset_tokens')
        ->where('email', $email)
        ->first();
}

    public function updatePassword(string $email, string $password)
    {
        return User::where('email', $email)->update([
            'password' => Hash::make($password)
        ]);
    }





}
