<?php

namespace App\Interfaces;

interface DealRepositoryInterface
{
    public function create(array $data);

public function findDealTypeByName(
    string $name
);
public function findDealById(int $id);

public function deleteDeal(int $id);


public function updateDeal(
    int $id,
    array $data
);
public function getDeals(
    array $filters
);

    //
}
