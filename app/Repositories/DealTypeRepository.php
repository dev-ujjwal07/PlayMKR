<?php

namespace App\Repositories;

use App\Models\DealType;
use App\Interfaces\DealTypeRepositoryInterface;

class DealTypeRepository
implements DealTypeRepositoryInterface
{
    public function create(array $data)
    {
        return DealType::create([

            'name' => $data['name']

        ]);
    }
}