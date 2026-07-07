<?php

namespace App\Interfaces;

interface AuthRepositoryInterface
{
    public function register(array $data);

    public function findUserByEmail(string $email);

    public function storeResetToken(
        string $email,
        string $token
    );

  public function findResetToken(string $email);

public function updatePassword(string $email, string $password);

public function getUserById(
    int $id
);

public function updateUser(
    int $userId,
    array $data
);


}