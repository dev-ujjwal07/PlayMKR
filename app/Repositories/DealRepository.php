<?php

namespace App\Repositories;

use App\Models\Deal;
use App\Models\DealType;
use App\Interfaces\DealRepositoryInterface;
use App\Models\Deliverable;

class DealRepository implements DealRepositoryInterface
{
    public function create(array $data)
    {
        return Deal::create($data);
    }

    public function findDealTypeByName(
        string $name
    )
    {
        return DealType::where(
            'name',
            $name
        )->first();
    }





    public function findDealById(int $id)
{
    return Deal::find($id);
}
public function deleteDeal(int $id)
{
    return Deal::where(
        'id',
        $id
    )->delete();
}


public function updateDeal(
    int $id,
    array $data
)
{
    Deal::where('id', $id)
        ->update($data);

    return Deal::find($id);
}


    







public function getDeals(
    array $filters
)
{
    $query = Deal::query()
        ->with([
            'sponsor:id,name',
            'dealType:id,name'
        ]);

    if (
        !empty($filters['search'])
    ) {

        $search =
            $filters['search'];

        $query->where(
            function ($q) use ($search) {

                if (is_numeric($search)) {

                    $q->orWhere(
                        'id',
                        $search
                    );
                }

                $q->orWhereHas(
                    'sponsor',
                    function ($sponsor) use ($search) {

                        $sponsor->where(
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




public function getDeliverables(
    array $filters
)
{
    $query = Deliverable::query()
        ->with([
            'deal:id,title',
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
                            'title',
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

    

    
}