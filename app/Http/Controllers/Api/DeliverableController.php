<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeliverableService;
use App\Http\Requests\DeliverableRequest;
use App\Constants\DeliverableConstants;
use App\Http\Requests\DeleteDeliverableRequest;
use App\Http\Requests\UpdateDeliverableRequest;
use Illuminate\Http\Request;


class DeliverableController extends Controller
{
    protected $deliverableService;

    public function __construct(
        DeliverableService $deliverableService
    )
    {
        $this->deliverableService =
            $deliverableService;
    }

    public function store(
        DeliverableRequest $request
    )
    {
        $data =
    $request->validated();

$data['attachment'] =
    $request->file(
        'attachment'
    );

$deliverable =
    $this->deliverableService
        ->create($data);

        return response()->json([

            'status' => true,

            'message' =>
                DeliverableConstants::DELIVERABLE_CREATED,

            'data' => $deliverable

        ], 201);
    }


    public function delete(
    DeleteDeliverableRequest $request
)
{
    $this->deliverableService
        ->delete(
            $request->id
        );

    return response()->json([

        'status' => true,

        'message' =>
            DeliverableConstants
                ::DELIVERABLE_DELETED

    ], 200);
}

public function update(
    UpdateDeliverableRequest $request
)
{
   $data =
    $request->validated();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
              
    $request->file(
        'attachment'
    );

$deliverable =
    $this->deliverableService
        ->update(
            $request->id,
            $data
        );

    return response()->json([

        'status' => true,

        'message' =>
            DeliverableConstants
                ::DELIVERABLE_UPDATED,

        'data' => $deliverable

    ], 200);

    
}





public function index(
    Request $request
)
{
    $deliverables =
        $this->deliverableService
            ->getDeliverables([

                'search' =>
                    $request->search,

                'status' =>
                    $request->status,

                'per_page' =>
                    $request->per_page
            ]);

    $formattedData =
        collect(
            $deliverables->items()
        )->map(
            function ($deliverable) {

                return [

                    'id' =>
                        $deliverable->id,

                    'deal_name' =>
                        $deliverable
                            ->deal?->deal_title,

                    'deliver_type_name' =>
                        $deliverable
                            ->deliverType?->name,

                    'team_name' =>
                        $deliverable
                            ->team?->name,

                    'title' =>
                        $deliverable->title,

                    'description' =>
                        $deliverable->description,

                    'quantity' =>
                        $deliverable->quantity,

                    'status' =>
                        $deliverable->status,

                    'priority' =>
                        $deliverable->priority,

                    'start_date' =>
                        $deliverable->start_date,

                    'due_date' =>
                        $deliverable->due_date,

                    'attachment' =>
                        $deliverable->attachment
                ];
            }
        );

    return response()->json([

        'status' => true,

        'message' =>
            'Deliverables fetched successfully',

        'data' =>
            $formattedData,

        'current_page' =>
            $deliverables->currentPage(),

        'last_page' =>
            $deliverables->lastPage(),

        'per_page' =>
            $deliverables->perPage(),

        'total' =>
            $deliverables->total()

    ]);
}





public function sponsorDeliverables(
    Request $request
)
{
    $result =
        $this->deliverableService
        ->getSponsorDeliverables([

            'search' =>
                $request->search,

            'per_page' =>
                $request->per_page
        ]);

    return response()->json([

        'status' => true,

        'message' =>
            'Deliverables fetched successfully',

        'data' =>
            $result['data'],

        'pagination' =>
            $result['pagination']
    ]);
}
}