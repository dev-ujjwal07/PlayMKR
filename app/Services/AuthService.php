<?php

namespace App\Services;

use Exception;
use App\Exceptions\RegistrationException;
use App\Interfaces\AuthRepositoryInterface;


class AuthService
{
    protected $authRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository
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



}