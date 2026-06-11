<?php

namespace App\Repositories;

use App\Models\Deliverable;
use App\Interfaces\DeliverableRepositoryInterface;
use App\Models\Sponsor;

class DeliverableRepository
implements DeliverableRepositoryInterface
{

    public function findSponsorByName(
    string $name
)
{
    return Sponsor::where(
        'name',
        $name
    )->first();
}



    public function create(array $data)
    {
        return Deliverable::create($data);
    }


}