<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DealRequest;
use App\Services\DealService;
use App\Http\Requests\DeleteDealRequest;
use App\Http\Requests\UpdateDealRequest;

class DealController extends Controller
{
    protected $dealService;

    public function __construct(
        DealService $dealService
    )
    {
        $this->dealService =
            $dealService;
    }

    public function store(
        DealRequest $request
    )
    {
        $deal = $this->dealService
            ->create(
                $request->validated()
            );

        return response()->json([

            'status' => true,

            'message' =>
                'Deal created successfully',

            'data' => $deal

        ], 201);
    }


public function deleteDeal(
    DeleteDealRequest $request
)
{
    $this->dealService
        ->deleteDeal(
            $request->validated()['id']
        );

    return response()->json([

        'status' => true,

        'message' =>
            'Deal deleted successfully'

    ], 200);
}




public function updateDeal(
    UpdateDealRequest $request
)
{
    $deal = $this->dealService
        ->updateDeal(
            $request->validated()
        );

    return response()->json([

        'status' => true,

        'message' =>
            'Deal updated successfully',

        'data' => $deal

    ], 200);
}


}