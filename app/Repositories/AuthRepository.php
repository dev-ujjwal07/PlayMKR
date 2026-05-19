<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\AuthRepositoryInterface;


class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
   return User::create([

    'name' => $data['full_name'],

    'full_name' => $data['full_name'],

    'email' => $data['email'],

    'password' => Hash::make($data['password'])

]);
    }




    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        $token = $user->createToken('play_mkr')->accessToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
