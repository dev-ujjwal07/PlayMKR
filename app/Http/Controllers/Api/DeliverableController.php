<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeliverableService;
use App\Http\Requests\DeliverableRequest;
use App\Constants\DeliverableConstants;
use App\Http\Requests\DeleteDeliverableRequest;
use App\Http\Requests\UpdateDeliverableRequest;


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
        $deliverable = $this->deliverableService
            ->create(
                $request->validated()
            );

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
    $deliverable =
        $this->deliverableService
            ->update(
                $request->id,
                $request->validated()
            );

    return response()->json([

        'status' => true,

        'message' =>
            DeliverableConstants
                ::DELIVERABLE_UPDATED,

        'data' => $deliverable

    ], 200);
}
}