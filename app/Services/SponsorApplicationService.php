<?php

namespace App\Services;

use Exception;
use App\Interfaces\SponsorApplicationRepositoryInterface;

class SponsorApplicationService
{
    protected $repository;

    public function __construct(
        SponsorApplicationRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function create(array $data)
    {
        try {

            return $this->repository
                        ->create($data);

        } catch (Exception $e) {

            throw new Exception(
                $e->getMessage()
            );
        }
    }




 
}