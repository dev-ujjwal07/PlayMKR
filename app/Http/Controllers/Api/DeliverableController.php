<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeliverableService;
use App\Http\Requests\DeliverableRequest;
use App\Constants\DeliverableConstants;

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
}