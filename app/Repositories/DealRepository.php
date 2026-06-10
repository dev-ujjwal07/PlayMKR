<?php

namespace App\Repositories;

use App\Models\Deal;
use App\Models\DealType;
use App\Interfaces\DealRepositoryInterface;

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
    

    

    
}