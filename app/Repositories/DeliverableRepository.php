<?php

namespace App\Repositories;

use App\Models\Deliverable;
use App\Interfaces\DeliverableRepositoryInterface;
use App\Models\Sponsor;
use App\Models\Team;

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

    public function findById(
    int $id
)
{
    return Deliverable::find($id);
}

public function delete(
    int $id
)
{
    return Deliverable::destroy($id);
}

public function update(
    int $id,
    array $data
)
{
    $deliverable =
        Deliverable::findOrFail($id);

    $deliverable->update($data);

    return $deliverable->fresh();
}

public function findTeamById(
    int $id
)
{
    return Team::find($id);
}

}