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



public function getDeliverables(
    array $filters
)
{
    $query = Deliverable::query()
        ->with([
            'deal:id,deal_title',
            'deliverType:id,name',
            'team:id,name'
        ]);

    if (
        !empty($filters['search'])
    ) {

        $search =
            $filters['search'];

        $query->where(
            function ($q) use ($search) {

                $q->whereHas(
                    'deal',
                    function ($deal) use ($search) {

                        $deal->where(
                            'deal_title',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                ->orWhereHas(
                    'team',
                    function ($team) use ($search) {

                        $team->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                );
            }
        );
    }

    if (
        !empty($filters['status'])
    ) {

        $query->where(
            'status',
            $filters['status']
        );
    }

    return $query
        ->latest('id')
        ->paginate(
            $filters['per_page'] ?? 10
        );
}


public function getSponsorDeliverables(
    int $sponsorId,
    array $filters
)
{
    $query = Deliverable::query()
        ->where(
            'sponsor_id',
            $sponsorId
        );

    if (
        !empty($filters['search'])
    ) {

        $search =
            $filters['search'];

        $query->where(
            function ($q) use ($search) {

                $q->where(
                    'title',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'status',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'deal',
                    function ($dealQuery)
                    use ($search) {

                        $dealQuery->where(
                            'deal_title',
                            'like',
                            "%{$search}%"
                        );
                    }
                );
            }
        );
    }

    $perPage =
        $filters['per_page']
        ?? 10;

    return $query
        ->latest('id')
        ->paginate($perPage);
}

}