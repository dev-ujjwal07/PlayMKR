<?php

namespace App\Repositories;

use App\Models\DeliverType;
use App\Interfaces\DeliverTypeRepositoryInterface;

class DeliverTypeRepository
implements DeliverTypeRepositoryInterface
{
    public function create(array $data)
    {
        return DeliverType::create($data);
    }

    public function findByName(
        string $name
    )
    {
        return DeliverType::where(
            'name',
            $name
        )->first();
    }
}