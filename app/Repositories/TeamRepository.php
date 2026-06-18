<?php

namespace App\Repositories;

use App\Models\Team;
use App\Models\Deliverable;
use App\Interfaces\TeamRepositoryInterface;

class TeamRepository
    implements TeamRepositoryInterface
{
    public function findDeliverableById(
        int $id
    )
    {
        return Deliverable::find($id);
    }

    public function findByEmail(
        string $email
    )
    {
        return Team::where(
            'email',
            $email
        )->first();
    }

    public function getLastTeam()
    {
        return Team::latest('id')
            ->first();
    }

    public function create(
        array $data
    )
    {
        return Team::create($data);
    }



    public function findById(
    int $id
)
{
    return Team::find($id);
}

public function update(
    int $id,
    array $data
)
{
    $team =
        Team::findOrFail($id);

    $team->update($data);

    return $team->fresh();
}

public function delete(
    int $id
)
{
    return Team::destroy($id);
}
}