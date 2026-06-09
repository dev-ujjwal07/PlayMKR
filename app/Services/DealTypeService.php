<?php

namespace App\Services;

use App\Interfaces\DealTypeRepositoryInterface;

class DealTypeService
{
    protected $dealTypeRepository;

    public function __construct(
        DealTypeRepositoryInterface $dealTypeRepository
    )
    {
        $this->dealTypeRepository =
            $dealTypeRepository;
    }

    public function create(array $data)
    {
        return $this->dealTypeRepository
            ->create($data);
    }
}