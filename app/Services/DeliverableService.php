<?php

namespace App\Services;

use Carbon\Carbon;
use App\Interfaces\DeliverableRepositoryInterface;
use Exception;
use App\Constants\DeliverableConstants;

class DeliverableService
{
    protected $deliverableRepository;

    public function __construct(
        DeliverableRepositoryInterface $deliverableRepository
    )
    {
        $this->deliverableRepository =
            $deliverableRepository;
    }

   public function create(array $data)
{
    $assignedSponsor = $this->deliverableRepository
        ->findSponsorByName(
            $data['assigned_to']
        );

    if (!$assignedSponsor) {

        throw new Exception(
            DeliverableConstants::SPONSOR_NOT_FOUND
        );
    }

    $data['assigned_to'] =
        $assignedSponsor->id;

    $data['status_updated_at'] =
        Carbon::now();

    return $this->deliverableRepository
        ->create($data);
}

}