<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
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



public function index(
    Request $request
)
{
    $deals =
        $this->dealService
            ->getDeals([

                'search' =>
                    $request->search,

                'status' =>
                    $request->status,

                'per_page' =>
                    $request->per_page
            ]);

    $formattedDeals =
        collect(
            $deals->items()
        )->map(
            function ($deal) {

                return [

                    'id' =>
                        $deal->id,

                    'sponsor_name' =>
                        $deal->sponsor?->name,

                    'deal_type_name' =>
                        $deal->dealType?->name,

                    'title' =>
                        $deal->title,

                    'status' =>
                        $deal->status,

                    'created_at' =>
                        $deal->created_at,

                    'updated_at' =>
                        $deal->updated_at
                ];
            }
        );

    return response()->json([

        'status' => true,

        'message' =>
            'Deals fetched successfully',

        'data' =>
            $formattedDeals,

        'current_page' =>
            $deals->currentPage(),

        'last_page' =>
            $deals->lastPage(),

        'per_page' =>
            $deals->perPage(),

        'total' =>
            $deals->total()

    ]);
}


}