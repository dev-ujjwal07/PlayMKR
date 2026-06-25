<?php

namespace App\Services;

use Exception;
use App\Interfaces\DealRepositoryInterface;
   

class DealService
{
    protected $dealRepository;

    public function __construct(
        DealRepositoryInterface $dealRepository
    )
    {
        $this->dealRepository =
            $dealRepository;
    }

    public function create(
        array $data
    )
    {
        $dealType = $this->dealRepository
            ->findDealTypeByName(
                $data['deal_type_name']
            );

        if (!$dealType) {

            throw new Exception(
                'Deal type not found'
            );
        }

        return $this->dealRepository
            ->create([

                'sponsor_id' =>
                    $data['sponsor_id'],

                'deal_title' =>
                    $data['deal_title'],

                'deal_description' =>
                    $data['deal_description'],

                'status' =>
                    $data['status'],

                'deal_type_id' =>
                    $dealType->id
            ]);
    }



 

public function deleteDeal(int $id)
{
    $deal = $this->dealRepository
        ->findDealById($id);

    if (!$deal) {

        throw new Exception(
            'Deal not found'
        );
    }

    $this->dealRepository
        ->deleteDeal($id);

    return true;
}

public function updateDeal(array $data)
{
    $deal = $this->dealRepository
        ->findDealById($data['id']);

    if (!$deal) {

        throw new Exception(
            'Deal not found'
        );
    }

    $dealType = $this->dealRepository
        ->findDealTypeByName(
            $data['deal_type_name']
        );

    if (!$dealType) {

        throw new Exception(
            'Deal type not found'
        );
    }

    return $this->dealRepository
        ->updateDeal(
            $data['id'],
            [

                'sponsor_id' =>
                    $data['sponsor_id'],

                'deal_title' =>
                    $data['deal_title'],

                'deal_description' =>
                    $data['deal_description'],

                'status' =>
                    $data['status'],

                'deal_type_id' =>
                    $dealType->id
            ]
        );
}



public function getDeals(
    array $filters
)
{
    return $this->dealRepository
        ->getDeals(
            $filters
        );
}

}