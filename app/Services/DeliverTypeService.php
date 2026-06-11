<?php

namespace App\Services;

use Exception;
use App\Constants\DeliverTypeConstants;
use App\Interfaces\DeliverTypeRepositoryInterface;

class DeliverTypeService
{
    protected $deliverTypeRepository;

    public function __construct(
        DeliverTypeRepositoryInterface $deliverTypeRepository
    )
    {
        $this->deliverTypeRepository =
            $deliverTypeRepository;
    }

    public function create(array $data)
    {
        $exists = $this->deliverTypeRepository
            ->findByName(
                $data['name']
            );

        if ($exists) {

            throw new Exception(
                DeliverTypeConstants::DELIVER_TYPE_ALREADY_EXISTS
            );
        }

        return $this->deliverTypeRepository
            ->create($data);
    }
}